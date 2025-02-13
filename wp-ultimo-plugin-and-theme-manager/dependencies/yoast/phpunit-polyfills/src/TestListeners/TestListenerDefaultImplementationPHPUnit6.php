<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\TestListeners;

use Exception;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
/**
 * Basic TestListener implementation for use with PHPUnit 6.x.
 *
 * This TestListener trait uses renamed (snakecase) methods for all standard methods in
 * a TestListener to get round the method signature changes in various PHPUnit versions.
 *
 * When using this TestListener trait, the snake_case method names need to be used to implement
 * the listener functionality.
 */
trait TestListenerDefaultImplementation
{
    use TestListenerSnakeCaseMethods;
    /**
     * An error occurred.
     *
     * @param Test      $test Test object.
     * @param Exception $e    Instance of the error encountered.
     * @param float     $time Execution time of this test.
     */
    public function addError(\PHPUnit\Framework\Test $test, \Exception $e, $time)
    {
        $this->add_error($test, $e, $time);
    }
    /**
     * A warning occurred.
     *
     * This method is only functional in PHPUnit 6.0 and above.
     *
     * @param Test    $test Test object.
     * @param Warning $e    Instance of the warning encountered.
     * @param float   $time Execution time of this test.
     */
    public function addWarning(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\Warning $e, $time)
    {
        $this->add_warning($test, $e, $time);
    }
    /**
     * A failure occurred.
     *
     * @param Test                 $test Test object.
     * @param AssertionFailedError $e    Instance of the assertion failure exception encountered.
     * @param float                $time Execution time of this test.
     */
    public function addFailure(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\AssertionFailedError $e, $time)
    {
        $this->add_failure($test, $e, $time);
    }
    /**
     * Incomplete test.
     *
     * @param Test      $test Test object.
     * @param Exception $e    Instance of the incomplete test exception.
     * @param float     $time Execution time of this test.
     */
    public function addIncompleteTest(\PHPUnit\Framework\Test $test, \Exception $e, $time)
    {
        $this->add_incomplete_test($test, $e, $time);
    }
    /**
     * Risky test.
     *
     * @param Test      $test Test object.
     * @param Exception $e    Instance of the risky test exception.
     * @param float     $time Execution time of this test.
     */
    public function addRiskyTest(\PHPUnit\Framework\Test $test, \Exception $e, $time)
    {
        $this->add_risky_test($test, $e, $time);
    }
    /**
     * Skipped test.
     *
     * @param Test      $test Test object.
     * @param Exception $e    Instance of the skipped test exception.
     * @param float     $time Execution time of this test.
     */
    public function addSkippedTest(\PHPUnit\Framework\Test $test, \Exception $e, $time)
    {
        $this->add_skipped_test($test, $e, $time);
    }
    /**
     * A test suite started.
     *
     * @param TestSuite $suite Test suite object.
     */
    public function startTestSuite(\PHPUnit\Framework\TestSuite $suite)
    {
        $this->start_test_suite($suite);
    }
    /**
     * A test suite ended.
     *
     * @param TestSuite $suite Test suite object.
     */
    public function endTestSuite(\PHPUnit\Framework\TestSuite $suite)
    {
        $this->end_test_suite($suite);
    }
    /**
     * A test started.
     *
     * @param Test $test Test object.
     */
    public function startTest(\PHPUnit\Framework\Test $test)
    {
        $this->start_test($test);
    }
    /**
     * A test ended.
     *
     * @param Test  $test Test object.
     * @param float $time Execution time of this test.
     */
    public function endTest(\PHPUnit\Framework\Test $test, $time)
    {
        $this->end_test($test, $time);
    }
}
