<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\TestCases;

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Helpers\AssertAttributeHelper;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\AssertClosedResource;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\AssertEqualsSpecializations;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\AssertFileDirectory;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\AssertFileEqualsSpecializations;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\AssertionRenames;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\AssertIsType;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\AssertNumericType;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\AssertObjectEquals;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\AssertStringContains;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\EqualToSpecializations;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\ExpectException;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\ExpectExceptionMessageMatches;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\ExpectExceptionObject;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Yoast\PHPUnitPolyfills\Polyfills\ExpectPHPException;
/**
 * Basic test case for use with PHPUnit cross-version.
 *
 * This test case uses renamed methods for the `setUpBeforeClass()`, `setUp()`, `tearDown()`
 * and `tearDownAfterClass()` methods to get round the signature change in PHPUnit 8.
 *
 * When using this TestCase, overloaded fixture methods need to use the `@beforeClass`, `@before`,
 * `@after` and `@afterClass` annotations.
 * The naming of the overloaded methods is open as long as the method names don't conflict with
 * the PHPUnit native method names.
 */
abstract class XTestCase extends \PHPUnit\Framework\TestCase
{
    use AssertAttributeHelper;
    use AssertClosedResource;
    use AssertEqualsSpecializations;
    use AssertFileDirectory;
    use AssertFileEqualsSpecializations;
    use AssertionRenames;
    use AssertIsType;
    use AssertNumericType;
    use AssertObjectEquals;
    use AssertStringContains;
    use EqualToSpecializations;
    use ExpectException;
    use ExpectExceptionMessageMatches;
    use ExpectExceptionObject;
    use ExpectPHPException;
    /**
     * This method is called before the first test of this test class is run.
     *
     * @beforeClass
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    public static function setUpFixturesBeforeClass()
    {
        parent::setUpBeforeClass();
    }
    /**
     * Sets up the fixture, for example, open a network connection.
     *
     * This method is called before each test.
     *
     * @before
     *
     * @return void
     */
    protected function setUpFixtures()
    {
        parent::setUp();
    }
    /**
     * Tears down the fixture, for example, close a network connection.
     *
     * This method is called after each test.
     *
     * @after
     *
     * @return void
     */
    protected function tearDownFixtures()
    {
        parent::tearDown();
    }
    /**
     * This method is called after the last test of this test class is run.
     *
     * @afterClass
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    public static function tearDownFixturesAfterClass()
    {
        parent::tearDownAfterClass();
    }
}
