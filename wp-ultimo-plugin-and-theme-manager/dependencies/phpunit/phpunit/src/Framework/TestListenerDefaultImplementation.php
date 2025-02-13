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
namespace PHPUnit\Framework;

use Throwable;
/**
 * @deprecated The `TestListener` interface is deprecated
 */
trait TestListenerDefaultImplementation
{
    public function addError(\PHPUnit\Framework\Test $test, \Throwable $t, float $time) : void
    {
    }
    public function addWarning(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\Warning $e, float $time) : void
    {
    }
    public function addFailure(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\AssertionFailedError $e, float $time) : void
    {
    }
    public function addIncompleteTest(\PHPUnit\Framework\Test $test, \Throwable $t, float $time) : void
    {
    }
    public function addRiskyTest(\PHPUnit\Framework\Test $test, \Throwable $t, float $time) : void
    {
    }
    public function addSkippedTest(\PHPUnit\Framework\Test $test, \Throwable $t, float $time) : void
    {
    }
    public function startTestSuite(\PHPUnit\Framework\TestSuite $suite) : void
    {
    }
    public function endTestSuite(\PHPUnit\Framework\TestSuite $suite) : void
    {
    }
    public function startTest(\PHPUnit\Framework\Test $test) : void
    {
    }
    public function endTest(\PHPUnit\Framework\Test $test, float $time) : void
    {
    }
}
