<?php

declare (strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\MockObject;

use function assert;
use function implode;
use function sprintf;
use Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Rule\AnyInvokedCount;
use PHPUnit\Framework\MockObject\Rule\AnyParameters;
use PHPUnit\Framework\MockObject\Rule\InvocationOrder;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\MockObject\Rule\MethodName;
use PHPUnit\Framework\MockObject\Rule\ParametersRule;
use PHPUnit\Framework\MockObject\Stub\Stub;
use PHPUnit\Framework\TestFailure;
/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class Matcher
{
    /**
     * @var InvocationOrder
     */
    private $invocationRule;
    /**
     * @var mixed
     */
    private $afterMatchBuilderId;
    /**
     * @var bool
     */
    private $afterMatchBuilderIsInvoked = \false;
    /**
     * @var MethodName
     */
    private $methodNameRule;
    /**
     * @var ParametersRule
     */
    private $parametersRule;
    /**
     * @var Stub
     */
    private $stub;
    public function __construct(\PHPUnit\Framework\MockObject\Rule\InvocationOrder $rule)
    {
        $this->invocationRule = $rule;
    }
    public function hasMatchers() : bool
    {
        return !$this->invocationRule instanceof \PHPUnit\Framework\MockObject\Rule\AnyInvokedCount;
    }
    public function hasMethodNameRule() : bool
    {
        return $this->methodNameRule !== null;
    }
    public function getMethodNameRule() : \PHPUnit\Framework\MockObject\Rule\MethodName
    {
        return $this->methodNameRule;
    }
    public function setMethodNameRule(\PHPUnit\Framework\MockObject\Rule\MethodName $rule) : void
    {
        $this->methodNameRule = $rule;
    }
    public function hasParametersRule() : bool
    {
        return $this->parametersRule !== null;
    }
    public function setParametersRule(\PHPUnit\Framework\MockObject\Rule\ParametersRule $rule) : void
    {
        $this->parametersRule = $rule;
    }
    public function setStub(\PHPUnit\Framework\MockObject\Stub\Stub $stub) : void
    {
        $this->stub = $stub;
    }
    public function setAfterMatchBuilderId(string $id) : void
    {
        $this->afterMatchBuilderId = $id;
    }
    /**
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws RuntimeException
     */
    public function invoked(\PHPUnit\Framework\MockObject\Invocation $invocation)
    {
        if ($this->methodNameRule === null) {
            throw new \PHPUnit\Framework\MockObject\RuntimeException('No method rule is set');
        }
        if ($this->afterMatchBuilderId !== null) {
            $matcher = $invocation->getObject()->__phpunit_getInvocationHandler()->lookupMatcher($this->afterMatchBuilderId);
            if (!$matcher) {
                throw new \PHPUnit\Framework\MockObject\RuntimeException(\sprintf('No builder found for match builder identification <%s>', $this->afterMatchBuilderId));
            }
            \assert($matcher instanceof self);
            if ($matcher->invocationRule->hasBeenInvoked()) {
                $this->afterMatchBuilderIsInvoked = \true;
            }
        }
        $this->invocationRule->invoked($invocation);
        try {
            if ($this->parametersRule !== null) {
                $this->parametersRule->apply($invocation);
            }
        } catch (\PHPUnit\Framework\ExpectationFailedException $e) {
            throw new \PHPUnit\Framework\ExpectationFailedException(\sprintf("Expectation failed for %s when %s\n%s", $this->methodNameRule->toString(), $this->invocationRule->toString(), $e->getMessage()), $e->getComparisonFailure());
        }
        if ($this->stub) {
            return $this->stub->invoke($invocation);
        }
        return $invocation->generateReturnValue();
    }
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     * @throws RuntimeException
     */
    public function matches(\PHPUnit\Framework\MockObject\Invocation $invocation) : bool
    {
        if ($this->afterMatchBuilderId !== null) {
            $matcher = $invocation->getObject()->__phpunit_getInvocationHandler()->lookupMatcher($this->afterMatchBuilderId);
            if (!$matcher) {
                throw new \PHPUnit\Framework\MockObject\RuntimeException(\sprintf('No builder found for match builder identification <%s>', $this->afterMatchBuilderId));
            }
            \assert($matcher instanceof self);
            if (!$matcher->invocationRule->hasBeenInvoked()) {
                return \false;
            }
        }
        if ($this->methodNameRule === null) {
            throw new \PHPUnit\Framework\MockObject\RuntimeException('No method rule is set');
        }
        if (!$this->invocationRule->matches($invocation)) {
            return \false;
        }
        try {
            if (!$this->methodNameRule->matches($invocation)) {
                return \false;
            }
        } catch (\PHPUnit\Framework\ExpectationFailedException $e) {
            throw new \PHPUnit\Framework\ExpectationFailedException(\sprintf("Expectation failed for %s when %s\n%s", $this->methodNameRule->toString(), $this->invocationRule->toString(), $e->getMessage()), $e->getComparisonFailure());
        }
        return \true;
    }
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     * @throws RuntimeException
     */
    public function verify() : void
    {
        if ($this->methodNameRule === null) {
            throw new \PHPUnit\Framework\MockObject\RuntimeException('No method rule is set');
        }
        try {
            $this->invocationRule->verify();
            if ($this->parametersRule === null) {
                $this->parametersRule = new \PHPUnit\Framework\MockObject\Rule\AnyParameters();
            }
            $invocationIsAny = $this->invocationRule instanceof \PHPUnit\Framework\MockObject\Rule\AnyInvokedCount;
            $invocationIsNever = $this->invocationRule instanceof \PHPUnit\Framework\MockObject\Rule\InvokedCount && $this->invocationRule->isNever();
            if (!$invocationIsAny && !$invocationIsNever) {
                $this->parametersRule->verify();
            }
        } catch (\PHPUnit\Framework\ExpectationFailedException $e) {
            throw new \PHPUnit\Framework\ExpectationFailedException(\sprintf("Expectation failed for %s when %s.\n%s", $this->methodNameRule->toString(), $this->invocationRule->toString(), \PHPUnit\Framework\TestFailure::exceptionToString($e)));
        }
    }
    public function toString() : string
    {
        $list = [];
        if ($this->invocationRule !== null) {
            $list[] = $this->invocationRule->toString();
        }
        if ($this->methodNameRule !== null) {
            $list[] = 'where ' . $this->methodNameRule->toString();
        }
        if ($this->parametersRule !== null) {
            $list[] = 'and ' . $this->parametersRule->toString();
        }
        if ($this->afterMatchBuilderId !== null) {
            $list[] = 'after ' . $this->afterMatchBuilderId;
        }
        if ($this->stub !== null) {
            $list[] = 'will ' . $this->stub->toString();
        }
        return \implode(' ', $list);
    }
}
