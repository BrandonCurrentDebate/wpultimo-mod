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
namespace PHPUnit\Util\TestDox;

use function array_filter;
use function get_class;
use function implode;
use DOMDocument;
use DOMElement;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use PHPUnit\Util\Printer;
use ReflectionClass;
use ReflectionException;
use Throwable;
/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class XmlResultPrinter extends \PHPUnit\Util\Printer implements \PHPUnit\Framework\TestListener
{
    /**
     * @var DOMDocument
     */
    private $document;
    /**
     * @var DOMElement
     */
    private $root;
    /**
     * @var NamePrettifier
     */
    private $prettifier;
    /**
     * @var null|Throwable
     */
    private $exception;
    /**
     * @param resource|string $out
     *
     * @throws Exception
     */
    public function __construct($out = null)
    {
        $this->document = new \DOMDocument('1.0', 'UTF-8');
        $this->document->formatOutput = \true;
        $this->root = $this->document->createElement('tests');
        $this->document->appendChild($this->root);
        $this->prettifier = new \PHPUnit\Util\TestDox\NamePrettifier();
        parent::__construct($out);
    }
    /**
     * Flush buffer and close output.
     */
    public function flush() : void
    {
        $this->write($this->document->saveXML());
        parent::flush();
    }
    /**
     * An error occurred.
     */
    public function addError(\PHPUnit\Framework\Test $test, \Throwable $t, float $time) : void
    {
        $this->exception = $t;
    }
    /**
     * A warning occurred.
     */
    public function addWarning(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\Warning $e, float $time) : void
    {
    }
    /**
     * A failure occurred.
     */
    public function addFailure(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\AssertionFailedError $e, float $time) : void
    {
        $this->exception = $e;
    }
    /**
     * Incomplete test.
     */
    public function addIncompleteTest(\PHPUnit\Framework\Test $test, \Throwable $t, float $time) : void
    {
    }
    /**
     * Risky test.
     */
    public function addRiskyTest(\PHPUnit\Framework\Test $test, \Throwable $t, float $time) : void
    {
    }
    /**
     * Skipped test.
     */
    public function addSkippedTest(\PHPUnit\Framework\Test $test, \Throwable $t, float $time) : void
    {
    }
    /**
     * A test suite started.
     */
    public function startTestSuite(\PHPUnit\Framework\TestSuite $suite) : void
    {
    }
    /**
     * A test suite ended.
     */
    public function endTestSuite(\PHPUnit\Framework\TestSuite $suite) : void
    {
    }
    /**
     * A test started.
     */
    public function startTest(\PHPUnit\Framework\Test $test) : void
    {
        $this->exception = null;
    }
    /**
     * A test ended.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function endTest(\PHPUnit\Framework\Test $test, float $time) : void
    {
        if (!$test instanceof \PHPUnit\Framework\TestCase) {
            return;
        }
        $groups = \array_filter($test->getGroups(), static function ($group) {
            return !($group === 'small' || $group === 'medium' || $group === 'large');
        });
        $testNode = $this->document->createElement('test');
        $testNode->setAttribute('className', \get_class($test));
        $testNode->setAttribute('methodName', $test->getName());
        $testNode->setAttribute('prettifiedClassName', $this->prettifier->prettifyTestClass(\get_class($test)));
        $testNode->setAttribute('prettifiedMethodName', $this->prettifier->prettifyTestCase($test));
        $testNode->setAttribute('status', (string) $test->getStatus());
        $testNode->setAttribute('time', (string) $time);
        $testNode->setAttribute('size', (string) $test->getSize());
        $testNode->setAttribute('groups', \implode(',', $groups));
        foreach ($groups as $group) {
            $groupNode = $this->document->createElement('group');
            $groupNode->setAttribute('name', $group);
            $testNode->appendChild($groupNode);
        }
        $annotations = $test->getAnnotations();
        foreach (['class', 'method'] as $type) {
            foreach ($annotations[$type] as $annotation => $values) {
                if ($annotation !== 'covers' && $annotation !== 'uses') {
                    continue;
                }
                foreach ($values as $value) {
                    $coversNode = $this->document->createElement($annotation);
                    $coversNode->setAttribute('target', $value);
                    $testNode->appendChild($coversNode);
                }
            }
        }
        foreach ($test->doubledTypes() as $doubledType) {
            $testDoubleNode = $this->document->createElement('testDouble');
            $testDoubleNode->setAttribute('type', $doubledType);
            $testNode->appendChild($testDoubleNode);
        }
        $inlineAnnotations = \PHPUnit\Util\Test::getInlineAnnotations(\get_class($test), $test->getName(\false));
        if (isset($inlineAnnotations['given'], $inlineAnnotations['when'], $inlineAnnotations['then'])) {
            $testNode->setAttribute('given', $inlineAnnotations['given']['value']);
            $testNode->setAttribute('givenStartLine', (string) $inlineAnnotations['given']['line']);
            $testNode->setAttribute('when', $inlineAnnotations['when']['value']);
            $testNode->setAttribute('whenStartLine', (string) $inlineAnnotations['when']['line']);
            $testNode->setAttribute('then', $inlineAnnotations['then']['value']);
            $testNode->setAttribute('thenStartLine', (string) $inlineAnnotations['then']['line']);
        }
        if ($this->exception !== null) {
            if ($this->exception instanceof \PHPUnit\Framework\Exception) {
                $steps = $this->exception->getSerializableTrace();
            } else {
                $steps = $this->exception->getTrace();
            }
            try {
                $file = (new \ReflectionClass($test))->getFileName();
                // @codeCoverageIgnoreStart
            } catch (\ReflectionException $e) {
                throw new \PHPUnit\Framework\Exception($e->getMessage(), (int) $e->getCode(), $e);
            }
            // @codeCoverageIgnoreEnd
            foreach ($steps as $step) {
                if (isset($step['file']) && $step['file'] === $file) {
                    $testNode->setAttribute('exceptionLine', (string) $step['line']);
                    break;
                }
            }
            $testNode->setAttribute('exceptionMessage', $this->exception->getMessage());
        }
        $this->root->appendChild($testNode);
    }
}
