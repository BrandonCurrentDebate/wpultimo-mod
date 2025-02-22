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
namespace PHPUnit\Framework\MockObject\Builder;

use function array_map;
use function array_merge;
use function count;
use function get_class;
use function gettype;
use function in_array;
use function is_object;
use function is_string;
use function sprintf;
use function strtolower;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\MockObject\ConfigurableMethod;
use PHPUnit\Framework\MockObject\IncompatibleReturnValueException;
use PHPUnit\Framework\MockObject\InvocationHandler;
use PHPUnit\Framework\MockObject\Matcher;
use PHPUnit\Framework\MockObject\Rule;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\MockObject\Stub\ConsecutiveCalls;
use PHPUnit\Framework\MockObject\Stub\Exception;
use PHPUnit\Framework\MockObject\Stub\ReturnArgument;
use PHPUnit\Framework\MockObject\Stub\ReturnCallback;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;
use PHPUnit\Framework\MockObject\Stub\ReturnSelf;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;
use PHPUnit\Framework\MockObject\Stub\ReturnValueMap;
use PHPUnit\Framework\MockObject\Stub\Stub;
use Throwable;
final class InvocationMocker implements \PHPUnit\Framework\MockObject\Builder\InvocationStubber, \PHPUnit\Framework\MockObject\Builder\MethodNameMatch
{
    /**
     * @var InvocationHandler
     */
    private $invocationHandler;
    /**
     * @var Matcher
     */
    private $matcher;
    /**
     * @var ConfigurableMethod[]
     */
    private $configurableMethods;
    public function __construct(\PHPUnit\Framework\MockObject\InvocationHandler $handler, \PHPUnit\Framework\MockObject\Matcher $matcher, \PHPUnit\Framework\MockObject\ConfigurableMethod ...$configurableMethods)
    {
        $this->invocationHandler = $handler;
        $this->matcher = $matcher;
        $this->configurableMethods = $configurableMethods;
    }
    /**
     * @return $this
     */
    public function id($id) : self
    {
        $this->invocationHandler->registerMatcher($id, $this->matcher);
        return $this;
    }
    /**
     * @return $this
     */
    public function will(\PHPUnit\Framework\MockObject\Stub\Stub $stub) : \PHPUnit\Framework\MockObject\Builder\Identity
    {
        $this->matcher->setStub($stub);
        return $this;
    }
    public function willReturn($value, ...$nextValues) : self
    {
        if (\count($nextValues) === 0) {
            $this->ensureTypeOfReturnValues([$value]);
            $stub = $value instanceof \PHPUnit\Framework\MockObject\Stub\Stub ? $value : new \PHPUnit\Framework\MockObject\Stub\ReturnStub($value);
        } else {
            $values = \array_merge([$value], $nextValues);
            $this->ensureTypeOfReturnValues($values);
            $stub = new \PHPUnit\Framework\MockObject\Stub\ConsecutiveCalls($values);
        }
        return $this->will($stub);
    }
    public function willReturnReference(&$reference) : self
    {
        $stub = new \PHPUnit\Framework\MockObject\Stub\ReturnReference($reference);
        return $this->will($stub);
    }
    public function willReturnMap(array $valueMap) : self
    {
        $stub = new \PHPUnit\Framework\MockObject\Stub\ReturnValueMap($valueMap);
        return $this->will($stub);
    }
    public function willReturnArgument($argumentIndex) : self
    {
        $stub = new \PHPUnit\Framework\MockObject\Stub\ReturnArgument($argumentIndex);
        return $this->will($stub);
    }
    public function willReturnCallback($callback) : self
    {
        $stub = new \PHPUnit\Framework\MockObject\Stub\ReturnCallback($callback);
        return $this->will($stub);
    }
    public function willReturnSelf() : self
    {
        $stub = new \PHPUnit\Framework\MockObject\Stub\ReturnSelf();
        return $this->will($stub);
    }
    public function willReturnOnConsecutiveCalls(...$values) : self
    {
        $stub = new \PHPUnit\Framework\MockObject\Stub\ConsecutiveCalls($values);
        return $this->will($stub);
    }
    public function willThrowException(\Throwable $exception) : self
    {
        $stub = new \PHPUnit\Framework\MockObject\Stub\Exception($exception);
        return $this->will($stub);
    }
    /**
     * @return $this
     */
    public function after($id) : self
    {
        $this->matcher->setAfterMatchBuilderId($id);
        return $this;
    }
    /**
     * @throws RuntimeException
     *
     * @return $this
     */
    public function with(...$arguments) : self
    {
        $this->canDefineParameters();
        $this->matcher->setParametersRule(new \PHPUnit\Framework\MockObject\Rule\Parameters($arguments));
        return $this;
    }
    /**
     * @param array ...$arguments
     *
     * @throws RuntimeException
     *
     * @return $this
     */
    public function withConsecutive(...$arguments) : self
    {
        $this->canDefineParameters();
        $this->matcher->setParametersRule(new \PHPUnit\Framework\MockObject\Rule\ConsecutiveParameters($arguments));
        return $this;
    }
    /**
     * @throws RuntimeException
     *
     * @return $this
     */
    public function withAnyParameters() : self
    {
        $this->canDefineParameters();
        $this->matcher->setParametersRule(new \PHPUnit\Framework\MockObject\Rule\AnyParameters());
        return $this;
    }
    /**
     * @param Constraint|string $constraint
     *
     * @throws RuntimeException
     *
     * @return $this
     */
    public function method($constraint) : self
    {
        if ($this->matcher->hasMethodNameRule()) {
            throw new \PHPUnit\Framework\MockObject\RuntimeException('Rule for method name is already defined, cannot redefine');
        }
        $configurableMethodNames = \array_map(static function (\PHPUnit\Framework\MockObject\ConfigurableMethod $configurable) {
            return \strtolower($configurable->getName());
        }, $this->configurableMethods);
        if (\is_string($constraint) && !\in_array(\strtolower($constraint), $configurableMethodNames, \true)) {
            throw new \PHPUnit\Framework\MockObject\RuntimeException(\sprintf('Trying to configure method "%s" which cannot be configured because it does not exist, has not been specified, is final, or is static', $constraint));
        }
        $this->matcher->setMethodNameRule(new \PHPUnit\Framework\MockObject\Rule\MethodName($constraint));
        return $this;
    }
    /**
     * Validate that a parameters rule can be defined, throw exceptions otherwise.
     *
     * @throws RuntimeException
     */
    private function canDefineParameters() : void
    {
        if (!$this->matcher->hasMethodNameRule()) {
            throw new \PHPUnit\Framework\MockObject\RuntimeException('Rule for method name is not defined, cannot define rule for parameters ' . 'without one');
        }
        if ($this->matcher->hasParametersRule()) {
            throw new \PHPUnit\Framework\MockObject\RuntimeException('Rule for parameters is already defined, cannot redefine');
        }
    }
    private function getConfiguredMethod() : ?\PHPUnit\Framework\MockObject\ConfigurableMethod
    {
        $configuredMethod = null;
        foreach ($this->configurableMethods as $configurableMethod) {
            if ($this->matcher->getMethodNameRule()->matchesName($configurableMethod->getName())) {
                if ($configuredMethod !== null) {
                    return null;
                }
                $configuredMethod = $configurableMethod;
            }
        }
        return $configuredMethod;
    }
    private function ensureTypeOfReturnValues(array $values) : void
    {
        $configuredMethod = $this->getConfiguredMethod();
        if ($configuredMethod === null) {
            return;
        }
        foreach ($values as $value) {
            if (!$configuredMethod->mayReturn($value)) {
                throw new \PHPUnit\Framework\MockObject\IncompatibleReturnValueException(\sprintf('Method %s may not return value of type %s, its return declaration is "%s"', $configuredMethod->getName(), \is_object($value) ? \get_class($value) : \gettype($value), $configuredMethod->getReturnTypeDeclaration()));
            }
        }
    }
}
