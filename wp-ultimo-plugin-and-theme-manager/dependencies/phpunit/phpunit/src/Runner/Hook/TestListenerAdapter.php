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

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use PHPUnit\Util\Test as TestUtil;
use Throwable;
/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class TestListenerAdapter implements \PHPUnit\Framework\TestListener
{
    /**
     * @var TestHook[]
     */
    private $hooks = [];
    /**
     * @var bool
     */
    private $lastTestWasNotSuccessful;
    public function add(\PHPUnit\Runner\TestHook $hook) : void
    {
        $this->hooks[] = $hook;
    }
    public function startTest(\PHPUnit\Framework\Test $test) : void
    {
        foreach ($this->hooks as $hook) {
            if ($hook instanceof \PHPUnit\Runner\BeforeTestHook) {
                $hook->executeBeforeTest(\PHPUnit\Util\Test::describeAsString($test));
            }
        }
        $this->lastTestWasNotSuccessful = \false;
    }
    public function addError(\PHPUnit\Framework\Test $test, \Throwable $t, float $time) : void
    {
        foreach ($this->hooks as $hook) {
            if ($hook instanceof \PHPUnit\Runner\AfterTestErrorHook) {
                $hook->executeAfterTestError(\PHPUnit\Util\Test::describeAsString($test), $t->getMessage(), $time);
            }
        }
        $this->lastTestWasNotSuccessful = \true;
    }
    public function addWarning(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\Warning $e, float $time) : void
    {
        foreach ($this->hooks as $hook) {
            if ($hook instanceof \PHPUnit\Runner\AfterTestWarningHook) {
                $hook->executeAfterTestWarning(\PHPUnit\Util\Test::describeAsString($test), $e->getMessage(), $time);
            }
        }
        $this->lastTestWasNotSuccessful = \true;
    }
    public function addFailure(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\AssertionFailedError $e, float $time) : void
    {
        foreach ($this->hooks as $hook) {
            if ($hook instanceof \PHPUnit\Runner\AfterTestFailureHook) {
                $hook->executeAfterTestFailure(\PHPUnit\Util\Test::describeAsString($test), $e->getMessage(), $time);
            }
        }
        $this->lastTestWasNotSuccessful = \true;
    }
    public function addIncompleteTest(\PHPUnit\Framework\Test $test, \Throwable $t, float $time) : void
    {
        foreach ($this->hooks as $hook) {
            if ($hook instanceof \PHPUnit\Runner\AfterIncompleteTestHook) {
                $hook->executeAfterIncompleteTest(\PHPUnit\Util\Test::describeAsString($test), $t->getMessage(), $time);
            }
        }
        $this->lastTestWasNotSuccessful = \true;
    }
    public function addRiskyTest(\PHPUnit\Framework\Test $test, \Throwable $t, float $time) : void
    {
        foreach ($this->hooks as $hook) {
            if ($hook instanceof \PHPUnit\Runner\AfterRiskyTestHook) {
                $hook->executeAfterRiskyTest(\PHPUnit\Util\Test::describeAsString($test), $t->getMessage(), $time);
            }
        }
        $this->lastTestWasNotSuccessful = \true;
    }
    public function addSkippedTest(\PHPUnit\Framework\Test $test, \Throwable $t, float $time) : void
    {
        foreach ($this->hooks as $hook) {
            if ($hook instanceof \PHPUnit\Runner\AfterSkippedTestHook) {
                $hook->executeAfterSkippedTest(\PHPUnit\Util\Test::describeAsString($test), $t->getMessage(), $time);
            }
        }
        $this->lastTestWasNotSuccessful = \true;
    }
    public function endTest(\PHPUnit\Framework\Test $test, float $time) : void
    {
        if (!$this->lastTestWasNotSuccessful) {
            foreach ($this->hooks as $hook) {
                if ($hook instanceof \PHPUnit\Runner\AfterSuccessfulTestHook) {
                    $hook->executeAfterSuccessfulTest(\PHPUnit\Util\Test::describeAsString($test), $time);
                }
            }
        }
        foreach ($this->hooks as $hook) {
            if ($hook instanceof \PHPUnit\Runner\AfterTestHook) {
                $hook->executeAfterTest(\PHPUnit\Util\Test::describeAsString($test), $time);
            }
        }
    }
    public function startTestSuite(\PHPUnit\Framework\TestSuite $suite) : void
    {
    }
    public function endTestSuite(\PHPUnit\Framework\TestSuite $suite) : void
    {
    }
}
