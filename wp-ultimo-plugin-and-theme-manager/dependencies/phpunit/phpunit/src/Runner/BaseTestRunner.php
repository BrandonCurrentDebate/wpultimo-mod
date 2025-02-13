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
namespace PHPUnit\Runner;

use function is_dir;
use function is_file;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestSuite;
use ReflectionClass;
use ReflectionException;
use SebastianBergmann\FileIterator\Facade as FileIteratorFacade;
/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
abstract class BaseTestRunner
{
    /**
     * @var int
     */
    public const STATUS_UNKNOWN = -1;
    /**
     * @var int
     */
    public const STATUS_PASSED = 0;
    /**
     * @var int
     */
    public const STATUS_SKIPPED = 1;
    /**
     * @var int
     */
    public const STATUS_INCOMPLETE = 2;
    /**
     * @var int
     */
    public const STATUS_FAILURE = 3;
    /**
     * @var int
     */
    public const STATUS_ERROR = 4;
    /**
     * @var int
     */
    public const STATUS_RISKY = 5;
    /**
     * @var int
     */
    public const STATUS_WARNING = 6;
    /**
     * @var string
     */
    public const SUITE_METHODNAME = 'suite';
    /**
     * Returns the loader to be used.
     */
    public function getLoader() : \PHPUnit\Runner\TestSuiteLoader
    {
        return new \PHPUnit\Runner\StandardTestSuiteLoader();
    }
    /**
     * Returns the Test corresponding to the given suite.
     * This is a template method, subclasses override
     * the runFailed() and clearStatus() methods.
     *
     * @param string|string[] $suffixes
     *
     * @throws Exception
     */
    public function getTest(string $suiteClassName, string $suiteClassFile = '', $suffixes = '') : ?\PHPUnit\Framework\Test
    {
        if (empty($suiteClassFile) && \is_dir($suiteClassName) && !\is_file($suiteClassName . '.php')) {
            /** @var string[] $files */
            $files = (new \SebastianBergmann\FileIterator\Facade())->getFilesAsArray($suiteClassName, $suffixes);
            $suite = new \PHPUnit\Framework\TestSuite($suiteClassName);
            $suite->addTestFiles($files);
            return $suite;
        }
        try {
            $testClass = $this->loadSuiteClass($suiteClassName, $suiteClassFile);
        } catch (\PHPUnit\Framework\Exception $e) {
            $this->runFailed($e->getMessage());
            return null;
        }
        try {
            $suiteMethod = $testClass->getMethod(self::SUITE_METHODNAME);
            if (!$suiteMethod->isStatic()) {
                $this->runFailed('suite() method must be static.');
                return null;
            }
            $test = $suiteMethod->invoke(null, $testClass->getName());
        } catch (\ReflectionException $e) {
            try {
                $test = new \PHPUnit\Framework\TestSuite($testClass);
            } catch (\PHPUnit\Framework\Exception $e) {
                $test = new \PHPUnit\Framework\TestSuite();
                $test->setName($suiteClassName);
            }
        }
        $this->clearStatus();
        return $test;
    }
    /**
     * Returns the loaded ReflectionClass for a suite name.
     */
    protected function loadSuiteClass(string $suiteClassName, string $suiteClassFile = '') : \ReflectionClass
    {
        return $this->getLoader()->load($suiteClassName, $suiteClassFile);
    }
    /**
     * Clears the status message.
     */
    protected function clearStatus() : void
    {
    }
    /**
     * Override to define how to handle a failed loading of
     * a test suite.
     */
    protected abstract function runFailed(string $message) : void;
}
