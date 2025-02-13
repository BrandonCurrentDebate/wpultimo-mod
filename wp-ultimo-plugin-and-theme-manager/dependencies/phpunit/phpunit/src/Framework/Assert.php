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

use const DEBUG_BACKTRACE_IGNORE_ARGS;
use const PHP_EOL;
use function array_key_exists;
use function array_shift;
use function array_unshift;
use function assert;
use function class_exists;
use function count;
use function debug_backtrace;
use function explode;
use function file_get_contents;
use function func_get_args;
use function implode;
use function interface_exists;
use function is_array;
use function is_bool;
use function is_int;
use function is_iterable;
use function is_object;
use function is_string;
use function preg_match;
use function preg_split;
use function sprintf;
use function strpos;
use ArrayAccess;
use Countable;
use DOMAttr;
use DOMDocument;
use DOMElement;
use PHPUnit\Framework\Constraint\ArrayHasKey;
use PHPUnit\Framework\Constraint\ArraySubset;
use PHPUnit\Framework\Constraint\Attribute;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\Constraint\ClassHasAttribute;
use PHPUnit\Framework\Constraint\ClassHasStaticAttribute;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\Constraint\DirectoryExists;
use PHPUnit\Framework\Constraint\FileExists;
use PHPUnit\Framework\Constraint\GreaterThan;
use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\Constraint\IsEmpty;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsFalse;
use PHPUnit\Framework\Constraint\IsFinite;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\IsInfinite;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\Constraint\IsJson;
use PHPUnit\Framework\Constraint\IsNan;
use PHPUnit\Framework\Constraint\IsNull;
use PHPUnit\Framework\Constraint\IsReadable;
use PHPUnit\Framework\Constraint\IsTrue;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\Constraint\IsWritable;
use PHPUnit\Framework\Constraint\JsonMatches;
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\LogicalAnd;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\LogicalOr;
use PHPUnit\Framework\Constraint\LogicalXor;
use PHPUnit\Framework\Constraint\ObjectHasAttribute;
use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\Constraint\SameSize;
use PHPUnit\Framework\Constraint\StringContains;
use PHPUnit\Framework\Constraint\StringEndsWith;
use PHPUnit\Framework\Constraint\StringMatchesFormatDescription;
use PHPUnit\Framework\Constraint\StringStartsWith;
use PHPUnit\Framework\Constraint\TraversableContains;
use PHPUnit\Framework\Constraint\TraversableContainsEqual;
use PHPUnit\Framework\Constraint\TraversableContainsIdentical;
use PHPUnit\Framework\Constraint\TraversableContainsOnly;
use PHPUnit\Util\Type;
use PHPUnit\Util\Xml;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use Traversable;
/**
 * A set of assertion methods.
 */
abstract class Assert
{
    /**
     * @var int
     */
    private static $count = 0;
    /**
     * Asserts that an array has a specified key.
     *
     * @param int|string        $key
     * @param array|ArrayAccess $array
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertArrayHasKey($key, $array, string $message = '') : void
    {
        if (!(\is_int($key) || \is_string($key))) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'integer or string');
        }
        if (!(\is_array($array) || $array instanceof \ArrayAccess)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'array or ArrayAccess');
        }
        $constraint = new \PHPUnit\Framework\Constraint\ArrayHasKey($key);
        static::assertThat($array, $constraint, $message);
    }
    /**
     * Asserts that an array has a specified subset.
     *
     * @param array|ArrayAccess $subset
     * @param array|ArrayAccess $array
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @codeCoverageIgnore
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3494
     */
    public static function assertArraySubset($subset, $array, bool $checkForObjectIdentity = \false, string $message = '') : void
    {
        self::createWarning('assertArraySubset() is deprecated and will be removed in PHPUnit 9.');
        if (!(\is_array($subset) || $subset instanceof \ArrayAccess)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'array or ArrayAccess');
        }
        if (!(\is_array($array) || $array instanceof \ArrayAccess)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'array or ArrayAccess');
        }
        $constraint = new \PHPUnit\Framework\Constraint\ArraySubset($subset, $checkForObjectIdentity);
        static::assertThat($array, $constraint, $message);
    }
    /**
     * Asserts that an array does not have a specified key.
     *
     * @param int|string        $key
     * @param array|ArrayAccess $array
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertArrayNotHasKey($key, $array, string $message = '') : void
    {
        if (!(\is_int($key) || \is_string($key))) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'integer or string');
        }
        if (!(\is_array($array) || $array instanceof \ArrayAccess)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'array or ArrayAccess');
        }
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\ArrayHasKey($key));
        static::assertThat($array, $constraint, $message);
    }
    /**
     * Asserts that a haystack contains a needle.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertContains($needle, $haystack, string $message = '', bool $ignoreCase = \false, bool $checkForObjectIdentity = \true, bool $checkForNonObjectIdentity = \false) : void
    {
        // @codeCoverageIgnoreStart
        if (\is_string($haystack)) {
            self::createWarning('Using assertContains() with string haystacks is deprecated and will not be supported in PHPUnit 9. Refactor your test to use assertStringContainsString() or assertStringContainsStringIgnoringCase() instead.');
        }
        if (!$checkForObjectIdentity) {
            self::createWarning('The optional $checkForObjectIdentity parameter of assertContains() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertContainsEquals() instead.');
        }
        if ($checkForNonObjectIdentity) {
            self::createWarning('The optional $checkForNonObjectIdentity parameter of assertContains() is deprecated and will be removed in PHPUnit 9.');
        }
        if ($ignoreCase) {
            self::createWarning('The optional $ignoreCase parameter of assertContains() is deprecated and will be removed in PHPUnit 9.');
        }
        // @codeCoverageIgnoreEnd
        if (\is_array($haystack) || \is_object($haystack) && $haystack instanceof \Traversable) {
            $constraint = new \PHPUnit\Framework\Constraint\TraversableContains($needle, $checkForObjectIdentity, $checkForNonObjectIdentity);
        } elseif (\is_string($haystack)) {
            if (!\is_string($needle)) {
                throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'string');
            }
            $constraint = new \PHPUnit\Framework\Constraint\StringContains($needle, $ignoreCase);
        } else {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'array, traversable or string');
        }
        static::assertThat($haystack, $constraint, $message);
    }
    public static function assertContainsEquals($needle, iterable $haystack, string $message = '') : void
    {
        $constraint = new \PHPUnit\Framework\Constraint\TraversableContainsEqual($needle);
        static::assertThat($haystack, $constraint, $message);
    }
    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object contains a needle.
     *
     * @param object|string $haystackClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeContains($needle, string $haystackAttributeName, $haystackClassOrObject, string $message = '', bool $ignoreCase = \false, bool $checkForObjectIdentity = \true, bool $checkForNonObjectIdentity = \false) : void
    {
        self::createWarning('assertAttributeContains() is deprecated and will be removed in PHPUnit 9.');
        static::assertContains($needle, static::readAttribute($haystackClassOrObject, $haystackAttributeName), $message, $ignoreCase, $checkForObjectIdentity, $checkForNonObjectIdentity);
    }
    /**
     * Asserts that a haystack does not contain a needle.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertNotContains($needle, $haystack, string $message = '', bool $ignoreCase = \false, bool $checkForObjectIdentity = \true, bool $checkForNonObjectIdentity = \false) : void
    {
        // @codeCoverageIgnoreStart
        if (\is_string($haystack)) {
            self::createWarning('Using assertNotContains() with string haystacks is deprecated and will not be supported in PHPUnit 9. Refactor your test to use assertStringNotContainsString() or assertStringNotContainsStringIgnoringCase() instead.');
        }
        if (!$checkForObjectIdentity) {
            self::createWarning('The optional $checkForObjectIdentity parameter of assertNotContains() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertNotContainsEquals() instead.');
        }
        if ($checkForNonObjectIdentity) {
            self::createWarning('The optional $checkForNonObjectIdentity parameter of assertNotContains() is deprecated and will be removed in PHPUnit 9.');
        }
        if ($ignoreCase) {
            self::createWarning('The optional $ignoreCase parameter of assertNotContains() is deprecated and will be removed in PHPUnit 9.');
        }
        // @codeCoverageIgnoreEnd
        if (\is_array($haystack) || \is_object($haystack) && $haystack instanceof \Traversable) {
            $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\TraversableContains($needle, $checkForObjectIdentity, $checkForNonObjectIdentity));
        } elseif (\is_string($haystack)) {
            if (!\is_string($needle)) {
                throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'string');
            }
            $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\StringContains($needle, $ignoreCase));
        } else {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'array, traversable or string');
        }
        static::assertThat($haystack, $constraint, $message);
    }
    public static function assertNotContainsEquals($needle, iterable $haystack, string $message = '') : void
    {
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\TraversableContainsEqual($needle));
        static::assertThat($haystack, $constraint, $message);
    }
    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object does not contain a needle.
     *
     * @param object|string $haystackClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeNotContains($needle, string $haystackAttributeName, $haystackClassOrObject, string $message = '', bool $ignoreCase = \false, bool $checkForObjectIdentity = \true, bool $checkForNonObjectIdentity = \false) : void
    {
        self::createWarning('assertAttributeNotContains() is deprecated and will be removed in PHPUnit 9.');
        static::assertNotContains($needle, static::readAttribute($haystackClassOrObject, $haystackAttributeName), $message, $ignoreCase, $checkForObjectIdentity, $checkForNonObjectIdentity);
    }
    /**
     * Asserts that a haystack contains only values of a given type.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertContainsOnly(string $type, iterable $haystack, ?bool $isNativeType = null, string $message = '') : void
    {
        if ($isNativeType === null) {
            $isNativeType = \PHPUnit\Util\Type::isType($type);
        }
        static::assertThat($haystack, new \PHPUnit\Framework\Constraint\TraversableContainsOnly($type, $isNativeType), $message);
    }
    /**
     * Asserts that a haystack contains only instances of a given class name.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertContainsOnlyInstancesOf(string $className, iterable $haystack, string $message = '') : void
    {
        static::assertThat($haystack, new \PHPUnit\Framework\Constraint\TraversableContainsOnly($className, \false), $message);
    }
    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object contains only values of a given type.
     *
     * @param object|string $haystackClassOrObject
     * @param bool          $isNativeType
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeContainsOnly(string $type, string $haystackAttributeName, $haystackClassOrObject, ?bool $isNativeType = null, string $message = '') : void
    {
        self::createWarning('assertAttributeContainsOnly() is deprecated and will be removed in PHPUnit 9.');
        static::assertContainsOnly($type, static::readAttribute($haystackClassOrObject, $haystackAttributeName), $isNativeType, $message);
    }
    /**
     * Asserts that a haystack does not contain only values of a given type.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertNotContainsOnly(string $type, iterable $haystack, ?bool $isNativeType = null, string $message = '') : void
    {
        if ($isNativeType === null) {
            $isNativeType = \PHPUnit\Util\Type::isType($type);
        }
        static::assertThat($haystack, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\TraversableContainsOnly($type, $isNativeType)), $message);
    }
    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object does not contain only values of a given
     * type.
     *
     * @param object|string $haystackClassOrObject
     * @param bool          $isNativeType
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeNotContainsOnly(string $type, string $haystackAttributeName, $haystackClassOrObject, ?bool $isNativeType = null, string $message = '') : void
    {
        self::createWarning('assertAttributeNotContainsOnly() is deprecated and will be removed in PHPUnit 9.');
        static::assertNotContainsOnly($type, static::readAttribute($haystackClassOrObject, $haystackAttributeName), $isNativeType, $message);
    }
    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param Countable|iterable $haystack
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertCount(int $expectedCount, $haystack, string $message = '') : void
    {
        if (!$haystack instanceof \Countable && !\is_iterable($haystack)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'countable or iterable');
        }
        static::assertThat($haystack, new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\count($expectedCount), $message);
    }
    /**
     * Asserts the number of elements of an array, Countable or Traversable
     * that is stored in an attribute.
     *
     * @param object|string $haystackClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeCount(int $expectedCount, string $haystackAttributeName, $haystackClassOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeCount() is deprecated and will be removed in PHPUnit 9.');
        static::assertCount($expectedCount, static::readAttribute($haystackClassOrObject, $haystackAttributeName), $message);
    }
    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param Countable|iterable $haystack
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertNotCount(int $expectedCount, $haystack, string $message = '') : void
    {
        if (!$haystack instanceof \Countable && !\is_iterable($haystack)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'countable or iterable');
        }
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\count($expectedCount));
        static::assertThat($haystack, $constraint, $message);
    }
    /**
     * Asserts the number of elements of an array, Countable or Traversable
     * that is stored in an attribute.
     *
     * @param object|string $haystackClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeNotCount(int $expectedCount, string $haystackAttributeName, $haystackClassOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeNotCount() is deprecated and will be removed in PHPUnit 9.');
        static::assertNotCount($expectedCount, static::readAttribute($haystackClassOrObject, $haystackAttributeName), $message);
    }
    /**
     * Asserts that two variables are equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertEquals($expected, $actual, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        // @codeCoverageIgnoreStart
        if ($delta !== 0.0) {
            self::createWarning('The optional $delta parameter of assertEquals() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertEqualsWithDelta() instead.');
        }
        if ($maxDepth !== 10) {
            self::createWarning('The optional $maxDepth parameter of assertEquals() is deprecated and will be removed in PHPUnit 9.');
        }
        if ($canonicalize) {
            self::createWarning('The optional $canonicalize parameter of assertEquals() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertEqualsCanonicalizing() instead.');
        }
        if ($ignoreCase) {
            self::createWarning('The optional $ignoreCase parameter of assertEquals() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertEqualsIgnoringCase() instead.');
        }
        // @codeCoverageIgnoreEnd
        $constraint = new \PHPUnit\Framework\Constraint\IsEqual($expected, $delta, $maxDepth, $canonicalize, $ignoreCase);
        static::assertThat($actual, $constraint, $message);
    }
    /**
     * Asserts that two variables are equal (canonicalizing).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertEqualsCanonicalizing($expected, $actual, string $message = '') : void
    {
        $constraint = new \PHPUnit\Framework\Constraint\IsEqual($expected, 0.0, 10, \true, \false);
        static::assertThat($actual, $constraint, $message);
    }
    /**
     * Asserts that two variables are equal (ignoring case).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertEqualsIgnoringCase($expected, $actual, string $message = '') : void
    {
        $constraint = new \PHPUnit\Framework\Constraint\IsEqual($expected, 0.0, 10, \false, \true);
        static::assertThat($actual, $constraint, $message);
    }
    /**
     * Asserts that two variables are equal (with delta).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertEqualsWithDelta($expected, $actual, float $delta, string $message = '') : void
    {
        $constraint = new \PHPUnit\Framework\Constraint\IsEqual($expected, $delta);
        static::assertThat($actual, $constraint, $message);
    }
    /**
     * Asserts that a variable is equal to an attribute of an object.
     *
     * @param object|string $actualClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeEquals($expected, string $actualAttributeName, $actualClassOrObject, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        self::createWarning('assertAttributeEquals() is deprecated and will be removed in PHPUnit 9.');
        static::assertEquals($expected, static::readAttribute($actualClassOrObject, $actualAttributeName), $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
    }
    /**
     * Asserts that two variables are not equal.
     *
     * @param float $delta
     * @param int   $maxDepth
     * @param bool  $canonicalize
     * @param bool  $ignoreCase
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertNotEquals($expected, $actual, string $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = \false, $ignoreCase = \false) : void
    {
        // @codeCoverageIgnoreStart
        if ($delta !== 0.0) {
            self::createWarning('The optional $delta parameter of assertNotEquals() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertNotEqualsWithDelta() instead.');
        }
        if ($maxDepth !== 10) {
            self::createWarning('The optional $maxDepth parameter of assertNotEquals() is deprecated and will be removed in PHPUnit 9.');
        }
        if ($canonicalize) {
            self::createWarning('The optional $canonicalize parameter of assertNotEquals() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertNotEqualsCanonicalizing() instead.');
        }
        if ($ignoreCase) {
            self::createWarning('The optional $ignoreCase parameter of assertNotEquals() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertNotEqualsIgnoringCase() instead.');
        }
        // @codeCoverageIgnoreEnd
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsEqual($expected, $delta, $maxDepth, $canonicalize, $ignoreCase));
        static::assertThat($actual, $constraint, $message);
    }
    /**
     * Asserts that two variables are not equal (canonicalizing).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertNotEqualsCanonicalizing($expected, $actual, string $message = '') : void
    {
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsEqual($expected, 0.0, 10, \true, \false));
        static::assertThat($actual, $constraint, $message);
    }
    /**
     * Asserts that two variables are not equal (ignoring case).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertNotEqualsIgnoringCase($expected, $actual, string $message = '') : void
    {
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsEqual($expected, 0.0, 10, \false, \true));
        static::assertThat($actual, $constraint, $message);
    }
    /**
     * Asserts that two variables are not equal (with delta).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertNotEqualsWithDelta($expected, $actual, float $delta, string $message = '') : void
    {
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsEqual($expected, $delta));
        static::assertThat($actual, $constraint, $message);
    }
    /**
     * Asserts that a variable is not equal to an attribute of an object.
     *
     * @param object|string $actualClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeNotEquals($expected, string $actualAttributeName, $actualClassOrObject, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        self::createWarning('assertAttributeNotEquals() is deprecated and will be removed in PHPUnit 9.');
        static::assertNotEquals($expected, static::readAttribute($actualClassOrObject, $actualAttributeName), $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
    }
    /**
     * Asserts that a variable is empty.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert empty $actual
     */
    public static function assertEmpty($actual, string $message = '') : void
    {
        static::assertThat($actual, static::isEmpty(), $message);
    }
    /**
     * Asserts that a static attribute of a class or an attribute of an object
     * is empty.
     *
     * @param object|string $haystackClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeEmpty(string $haystackAttributeName, $haystackClassOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeEmpty() is deprecated and will be removed in PHPUnit 9.');
        static::assertEmpty(static::readAttribute($haystackClassOrObject, $haystackAttributeName), $message);
    }
    /**
     * Asserts that a variable is not empty.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !empty $actual
     */
    public static function assertNotEmpty($actual, string $message = '') : void
    {
        static::assertThat($actual, static::logicalNot(static::isEmpty()), $message);
    }
    /**
     * Asserts that a static attribute of a class or an attribute of an object
     * is not empty.
     *
     * @param object|string $haystackClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeNotEmpty(string $haystackAttributeName, $haystackClassOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeNotEmpty() is deprecated and will be removed in PHPUnit 9.');
        static::assertNotEmpty(static::readAttribute($haystackClassOrObject, $haystackAttributeName), $message);
    }
    /**
     * Asserts that a value is greater than another value.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertGreaterThan($expected, $actual, string $message = '') : void
    {
        static::assertThat($actual, static::greaterThan($expected), $message);
    }
    /**
     * Asserts that an attribute is greater than another value.
     *
     * @param object|string $actualClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeGreaterThan($expected, string $actualAttributeName, $actualClassOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeGreaterThan() is deprecated and will be removed in PHPUnit 9.');
        static::assertGreaterThan($expected, static::readAttribute($actualClassOrObject, $actualAttributeName), $message);
    }
    /**
     * Asserts that a value is greater than or equal to another value.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertGreaterThanOrEqual($expected, $actual, string $message = '') : void
    {
        static::assertThat($actual, static::greaterThanOrEqual($expected), $message);
    }
    /**
     * Asserts that an attribute is greater than or equal to another value.
     *
     * @param object|string $actualClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeGreaterThanOrEqual($expected, string $actualAttributeName, $actualClassOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeGreaterThanOrEqual() is deprecated and will be removed in PHPUnit 9.');
        static::assertGreaterThanOrEqual($expected, static::readAttribute($actualClassOrObject, $actualAttributeName), $message);
    }
    /**
     * Asserts that a value is smaller than another value.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertLessThan($expected, $actual, string $message = '') : void
    {
        static::assertThat($actual, static::lessThan($expected), $message);
    }
    /**
     * Asserts that an attribute is smaller than another value.
     *
     * @param object|string $actualClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeLessThan($expected, string $actualAttributeName, $actualClassOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeLessThan() is deprecated and will be removed in PHPUnit 9.');
        static::assertLessThan($expected, static::readAttribute($actualClassOrObject, $actualAttributeName), $message);
    }
    /**
     * Asserts that a value is smaller than or equal to another value.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertLessThanOrEqual($expected, $actual, string $message = '') : void
    {
        static::assertThat($actual, static::lessThanOrEqual($expected), $message);
    }
    /**
     * Asserts that an attribute is smaller than or equal to another value.
     *
     * @param object|string $actualClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeLessThanOrEqual($expected, string $actualAttributeName, $actualClassOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeLessThanOrEqual() is deprecated and will be removed in PHPUnit 9.');
        static::assertLessThanOrEqual($expected, static::readAttribute($actualClassOrObject, $actualAttributeName), $message);
    }
    /**
     * Asserts that the contents of one file is equal to the contents of another
     * file.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFileEquals(string $expected, string $actual, string $message = '', bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        // @codeCoverageIgnoreStart
        if ($canonicalize) {
            self::createWarning('The optional $canonicalize parameter of assertFileEquals() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertFileEqualsCanonicalizing() instead.');
        }
        if ($ignoreCase) {
            self::createWarning('The optional $ignoreCase parameter of assertFileEquals() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertFileEqualsIgnoringCase() instead.');
        }
        // @codeCoverageIgnoreEnd
        static::assertFileExists($expected, $message);
        static::assertFileExists($actual, $message);
        $constraint = new \PHPUnit\Framework\Constraint\IsEqual(\file_get_contents($expected), 0.0, 10, $canonicalize, $ignoreCase);
        static::assertThat(\file_get_contents($actual), $constraint, $message);
    }
    /**
     * Asserts that the contents of one file is equal to the contents of another
     * file (canonicalizing).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFileEqualsCanonicalizing(string $expected, string $actual, string $message = '') : void
    {
        static::assertFileExists($expected, $message);
        static::assertFileExists($actual, $message);
        $constraint = new \PHPUnit\Framework\Constraint\IsEqual(\file_get_contents($expected), 0.0, 10, \true);
        static::assertThat(\file_get_contents($actual), $constraint, $message);
    }
    /**
     * Asserts that the contents of one file is equal to the contents of another
     * file (ignoring case).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFileEqualsIgnoringCase(string $expected, string $actual, string $message = '') : void
    {
        static::assertFileExists($expected, $message);
        static::assertFileExists($actual, $message);
        $constraint = new \PHPUnit\Framework\Constraint\IsEqual(\file_get_contents($expected), 0.0, 10, \false, \true);
        static::assertThat(\file_get_contents($actual), $constraint, $message);
    }
    /**
     * Asserts that the contents of one file is not equal to the contents of
     * another file.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFileNotEquals(string $expected, string $actual, string $message = '', bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        // @codeCoverageIgnoreStart
        if ($canonicalize) {
            self::createWarning('The optional $canonicalize parameter of assertFileNotEquals() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertFileNotEqualsCanonicalizing() instead.');
        }
        if ($ignoreCase) {
            self::createWarning('The optional $ignoreCase parameter of assertFileNotEquals() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertFileNotEqualsIgnoringCase() instead.');
        }
        // @codeCoverageIgnoreEnd
        static::assertFileExists($expected, $message);
        static::assertFileExists($actual, $message);
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsEqual(\file_get_contents($expected), 0.0, 10, $canonicalize, $ignoreCase));
        static::assertThat(\file_get_contents($actual), $constraint, $message);
    }
    /**
     * Asserts that the contents of one file is not equal to the contents of another
     * file (canonicalizing).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFileNotEqualsCanonicalizing(string $expected, string $actual, string $message = '') : void
    {
        static::assertFileExists($expected, $message);
        static::assertFileExists($actual, $message);
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsEqual(\file_get_contents($expected), 0.0, 10, \true));
        static::assertThat(\file_get_contents($actual), $constraint, $message);
    }
    /**
     * Asserts that the contents of one file is not equal to the contents of another
     * file (ignoring case).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFileNotEqualsIgnoringCase(string $expected, string $actual, string $message = '') : void
    {
        static::assertFileExists($expected, $message);
        static::assertFileExists($actual, $message);
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsEqual(\file_get_contents($expected), 0.0, 10, \false, \true));
        static::assertThat(\file_get_contents($actual), $constraint, $message);
    }
    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringEqualsFile(string $expectedFile, string $actualString, string $message = '', bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        // @codeCoverageIgnoreStart
        if ($canonicalize) {
            self::createWarning('The optional $canonicalize parameter of assertStringEqualsFile() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertStringEqualsFileCanonicalizing() instead.');
        }
        if ($ignoreCase) {
            self::createWarning('The optional $ignoreCase parameter of assertStringEqualsFile() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertStringEqualsFileIgnoringCase() instead.');
        }
        // @codeCoverageIgnoreEnd
        static::assertFileExists($expectedFile, $message);
        $constraint = new \PHPUnit\Framework\Constraint\IsEqual(\file_get_contents($expectedFile), 0.0, 10, $canonicalize, $ignoreCase);
        static::assertThat($actualString, $constraint, $message);
    }
    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file (canonicalizing).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringEqualsFileCanonicalizing(string $expectedFile, string $actualString, string $message = '') : void
    {
        static::assertFileExists($expectedFile, $message);
        $constraint = new \PHPUnit\Framework\Constraint\IsEqual(\file_get_contents($expectedFile), 0.0, 10, \true);
        static::assertThat($actualString, $constraint, $message);
    }
    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file (ignoring case).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringEqualsFileIgnoringCase(string $expectedFile, string $actualString, string $message = '') : void
    {
        static::assertFileExists($expectedFile, $message);
        $constraint = new \PHPUnit\Framework\Constraint\IsEqual(\file_get_contents($expectedFile), 0.0, 10, \false, \true);
        static::assertThat($actualString, $constraint, $message);
    }
    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringNotEqualsFile(string $expectedFile, string $actualString, string $message = '', bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        // @codeCoverageIgnoreStart
        if ($canonicalize) {
            self::createWarning('The optional $canonicalize parameter of assertStringNotEqualsFile() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertStringNotEqualsFileCanonicalizing() instead.');
        }
        if ($ignoreCase) {
            self::createWarning('The optional $ignoreCase parameter of assertStringNotEqualsFile() is deprecated and will be removed in PHPUnit 9. Refactor your test to use assertStringNotEqualsFileIgnoringCase() instead.');
        }
        // @codeCoverageIgnoreEnd
        static::assertFileExists($expectedFile, $message);
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsEqual(\file_get_contents($expectedFile), 0.0, 10, $canonicalize, $ignoreCase));
        static::assertThat($actualString, $constraint, $message);
    }
    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file (canonicalizing).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringNotEqualsFileCanonicalizing(string $expectedFile, string $actualString, string $message = '') : void
    {
        static::assertFileExists($expectedFile, $message);
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsEqual(\file_get_contents($expectedFile), 0.0, 10, \true));
        static::assertThat($actualString, $constraint, $message);
    }
    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file (ignoring case).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringNotEqualsFileIgnoringCase(string $expectedFile, string $actualString, string $message = '') : void
    {
        static::assertFileExists($expectedFile, $message);
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsEqual(\file_get_contents($expectedFile), 0.0, 10, \false, \true));
        static::assertThat($actualString, $constraint, $message);
    }
    /**
     * Asserts that a file/dir is readable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertIsReadable(string $filename, string $message = '') : void
    {
        static::assertThat($filename, new \PHPUnit\Framework\Constraint\IsReadable(), $message);
    }
    /**
     * Asserts that a file/dir exists and is not readable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertNotIsReadable(string $filename, string $message = '') : void
    {
        static::assertThat($filename, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsReadable()), $message);
    }
    /**
     * Asserts that a file/dir exists and is writable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertIsWritable(string $filename, string $message = '') : void
    {
        static::assertThat($filename, new \PHPUnit\Framework\Constraint\IsWritable(), $message);
    }
    /**
     * Asserts that a file/dir exists and is not writable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertNotIsWritable(string $filename, string $message = '') : void
    {
        static::assertThat($filename, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsWritable()), $message);
    }
    /**
     * Asserts that a directory exists.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertDirectoryExists(string $directory, string $message = '') : void
    {
        static::assertThat($directory, new \PHPUnit\Framework\Constraint\DirectoryExists(), $message);
    }
    /**
     * Asserts that a directory does not exist.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertDirectoryNotExists(string $directory, string $message = '') : void
    {
        static::assertThat($directory, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\DirectoryExists()), $message);
    }
    /**
     * Asserts that a directory exists and is readable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertDirectoryIsReadable(string $directory, string $message = '') : void
    {
        self::assertDirectoryExists($directory, $message);
        self::assertIsReadable($directory, $message);
    }
    /**
     * Asserts that a directory exists and is not readable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertDirectoryNotIsReadable(string $directory, string $message = '') : void
    {
        self::assertDirectoryExists($directory, $message);
        self::assertNotIsReadable($directory, $message);
    }
    /**
     * Asserts that a directory exists and is writable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertDirectoryIsWritable(string $directory, string $message = '') : void
    {
        self::assertDirectoryExists($directory, $message);
        self::assertIsWritable($directory, $message);
    }
    /**
     * Asserts that a directory exists and is not writable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertDirectoryNotIsWritable(string $directory, string $message = '') : void
    {
        self::assertDirectoryExists($directory, $message);
        self::assertNotIsWritable($directory, $message);
    }
    /**
     * Asserts that a file exists.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFileExists(string $filename, string $message = '') : void
    {
        static::assertThat($filename, new \PHPUnit\Framework\Constraint\FileExists(), $message);
    }
    /**
     * Asserts that a file does not exist.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFileNotExists(string $filename, string $message = '') : void
    {
        static::assertThat($filename, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\FileExists()), $message);
    }
    /**
     * Asserts that a file exists and is readable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFileIsReadable(string $file, string $message = '') : void
    {
        self::assertFileExists($file, $message);
        self::assertIsReadable($file, $message);
    }
    /**
     * Asserts that a file exists and is not readable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFileNotIsReadable(string $file, string $message = '') : void
    {
        self::assertFileExists($file, $message);
        self::assertNotIsReadable($file, $message);
    }
    /**
     * Asserts that a file exists and is writable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFileIsWritable(string $file, string $message = '') : void
    {
        self::assertFileExists($file, $message);
        self::assertIsWritable($file, $message);
    }
    /**
     * Asserts that a file exists and is not writable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFileNotIsWritable(string $file, string $message = '') : void
    {
        self::assertFileExists($file, $message);
        self::assertNotIsWritable($file, $message);
    }
    /**
     * Asserts that a condition is true.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert true $condition
     */
    public static function assertTrue($condition, string $message = '') : void
    {
        static::assertThat($condition, static::isTrue(), $message);
    }
    /**
     * Asserts that a condition is not true.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !true $condition
     */
    public static function assertNotTrue($condition, string $message = '') : void
    {
        static::assertThat($condition, static::logicalNot(static::isTrue()), $message);
    }
    /**
     * Asserts that a condition is false.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert false $condition
     */
    public static function assertFalse($condition, string $message = '') : void
    {
        static::assertThat($condition, static::isFalse(), $message);
    }
    /**
     * Asserts that a condition is not false.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !false $condition
     */
    public static function assertNotFalse($condition, string $message = '') : void
    {
        static::assertThat($condition, static::logicalNot(static::isFalse()), $message);
    }
    /**
     * Asserts that a variable is null.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert null $actual
     */
    public static function assertNull($actual, string $message = '') : void
    {
        static::assertThat($actual, static::isNull(), $message);
    }
    /**
     * Asserts that a variable is not null.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !null $actual
     */
    public static function assertNotNull($actual, string $message = '') : void
    {
        static::assertThat($actual, static::logicalNot(static::isNull()), $message);
    }
    /**
     * Asserts that a variable is finite.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertFinite($actual, string $message = '') : void
    {
        static::assertThat($actual, static::isFinite(), $message);
    }
    /**
     * Asserts that a variable is infinite.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertInfinite($actual, string $message = '') : void
    {
        static::assertThat($actual, static::isInfinite(), $message);
    }
    /**
     * Asserts that a variable is nan.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertNan($actual, string $message = '') : void
    {
        static::assertThat($actual, static::isNan(), $message);
    }
    /**
     * Asserts that a class has a specified attribute.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertClassHasAttribute(string $attributeName, string $className, string $message = '') : void
    {
        if (!self::isValidClassAttributeName($attributeName)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'valid attribute name');
        }
        if (!\class_exists($className)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'class name');
        }
        static::assertThat($className, new \PHPUnit\Framework\Constraint\ClassHasAttribute($attributeName), $message);
    }
    /**
     * Asserts that a class does not have a specified attribute.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertClassNotHasAttribute(string $attributeName, string $className, string $message = '') : void
    {
        if (!self::isValidClassAttributeName($attributeName)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'valid attribute name');
        }
        if (!\class_exists($className)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'class name');
        }
        static::assertThat($className, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\ClassHasAttribute($attributeName)), $message);
    }
    /**
     * Asserts that a class has a specified static attribute.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertClassHasStaticAttribute(string $attributeName, string $className, string $message = '') : void
    {
        if (!self::isValidClassAttributeName($attributeName)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'valid attribute name');
        }
        if (!\class_exists($className)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'class name');
        }
        static::assertThat($className, new \PHPUnit\Framework\Constraint\ClassHasStaticAttribute($attributeName), $message);
    }
    /**
     * Asserts that a class does not have a specified static attribute.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertClassNotHasStaticAttribute(string $attributeName, string $className, string $message = '') : void
    {
        if (!self::isValidClassAttributeName($attributeName)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'valid attribute name');
        }
        if (!\class_exists($className)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'class name');
        }
        static::assertThat($className, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\ClassHasStaticAttribute($attributeName)), $message);
    }
    /**
     * Asserts that an object has a specified attribute.
     *
     * @param object $object
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertObjectHasAttribute(string $attributeName, $object, string $message = '') : void
    {
        if (!self::isValidObjectAttributeName($attributeName)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'valid attribute name');
        }
        if (!\is_object($object)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'object');
        }
        static::assertThat($object, new \PHPUnit\Framework\Constraint\ObjectHasAttribute($attributeName), $message);
    }
    /**
     * Asserts that an object does not have a specified attribute.
     *
     * @param object $object
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertObjectNotHasAttribute(string $attributeName, $object, string $message = '') : void
    {
        if (!self::isValidObjectAttributeName($attributeName)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'valid attribute name');
        }
        if (!\is_object($object)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'object');
        }
        static::assertThat($object, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\ObjectHasAttribute($attributeName)), $message);
    }
    /**
     * Asserts that two variables have the same type and value.
     * Used on objects, it asserts that two variables reference
     * the same object.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-template ExpectedType
     * @psalm-param ExpectedType $expected
     * @psalm-assert =ExpectedType $actual
     */
    public static function assertSame($expected, $actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsIdentical($expected), $message);
    }
    /**
     * Asserts that a variable and an attribute of an object have the same type
     * and value.
     *
     * @param object|string $actualClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeSame($expected, string $actualAttributeName, $actualClassOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeSame() is deprecated and will be removed in PHPUnit 9.');
        static::assertSame($expected, static::readAttribute($actualClassOrObject, $actualAttributeName), $message);
    }
    /**
     * Asserts that two variables do not have the same type and value.
     * Used on objects, it asserts that two variables do not reference
     * the same object.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertNotSame($expected, $actual, string $message = '') : void
    {
        if (\is_bool($expected) && \is_bool($actual)) {
            static::assertNotEquals($expected, $actual, $message);
        }
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsIdentical($expected)), $message);
    }
    /**
     * Asserts that a variable and an attribute of an object do not have the
     * same type and value.
     *
     * @param object|string $actualClassOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeNotSame($expected, string $actualAttributeName, $actualClassOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeNotSame() is deprecated and will be removed in PHPUnit 9.');
        static::assertNotSame($expected, static::readAttribute($actualClassOrObject, $actualAttributeName), $message);
    }
    /**
     * Asserts that a variable is of a given type.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @psalm-template ExpectedType of object
     * @psalm-param class-string<ExpectedType> $expected
     * @psalm-assert =ExpectedType $actual
     */
    public static function assertInstanceOf(string $expected, $actual, string $message = '') : void
    {
        if (!\class_exists($expected) && !\interface_exists($expected)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'class or interface name');
        }
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsInstanceOf($expected), $message);
    }
    /**
     * Asserts that an attribute is of a given type.
     *
     * @param object|string $classOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     *
     * @psalm-param class-string $expected
     */
    public static function assertAttributeInstanceOf(string $expected, string $attributeName, $classOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeInstanceOf() is deprecated and will be removed in PHPUnit 9.');
        static::assertInstanceOf($expected, static::readAttribute($classOrObject, $attributeName), $message);
    }
    /**
     * Asserts that a variable is not of a given type.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @psalm-template ExpectedType of object
     * @psalm-param class-string<ExpectedType> $expected
     * @psalm-assert !ExpectedType $actual
     */
    public static function assertNotInstanceOf(string $expected, $actual, string $message = '') : void
    {
        if (!\class_exists($expected) && !\interface_exists($expected)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'class or interface name');
        }
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsInstanceOf($expected)), $message);
    }
    /**
     * Asserts that an attribute is of a given type.
     *
     * @param object|string $classOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     *
     * @psalm-param class-string $expected
     */
    public static function assertAttributeNotInstanceOf(string $expected, string $attributeName, $classOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeNotInstanceOf() is deprecated and will be removed in PHPUnit 9.');
        static::assertNotInstanceOf($expected, static::readAttribute($classOrObject, $attributeName), $message);
    }
    /**
     * Asserts that a variable is of a given type.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3369
     * @codeCoverageIgnore
     */
    public static function assertInternalType(string $expected, $actual, string $message = '') : void
    {
        self::createWarning(\sprintf('assertInternalType() is deprecated and will be removed in PHPUnit 9. Refactor your test to use %s() instead.', self::assertInternalTypeReplacement($expected, \false)));
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsType($expected), $message);
    }
    /**
     * Asserts that an attribute is of a given type.
     *
     * @param object|string $classOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeInternalType(string $expected, string $attributeName, $classOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeInternalType() is deprecated and will be removed in PHPUnit 9.');
        static::assertInternalType($expected, static::readAttribute($classOrObject, $attributeName), $message);
    }
    /**
     * Asserts that a variable is of type array.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert array $actual
     */
    public static function assertIsArray($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_ARRAY), $message);
    }
    /**
     * Asserts that a variable is of type bool.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert bool $actual
     */
    public static function assertIsBool($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_BOOL), $message);
    }
    /**
     * Asserts that a variable is of type float.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert float $actual
     */
    public static function assertIsFloat($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_FLOAT), $message);
    }
    /**
     * Asserts that a variable is of type int.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert int $actual
     */
    public static function assertIsInt($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_INT), $message);
    }
    /**
     * Asserts that a variable is of type numeric.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert numeric $actual
     */
    public static function assertIsNumeric($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_NUMERIC), $message);
    }
    /**
     * Asserts that a variable is of type object.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert object $actual
     */
    public static function assertIsObject($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_OBJECT), $message);
    }
    /**
     * Asserts that a variable is of type resource.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert resource $actual
     */
    public static function assertIsResource($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_RESOURCE), $message);
    }
    /**
     * Asserts that a variable is of type string.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert string $actual
     */
    public static function assertIsString($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_STRING), $message);
    }
    /**
     * Asserts that a variable is of type scalar.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert scalar $actual
     */
    public static function assertIsScalar($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_SCALAR), $message);
    }
    /**
     * Asserts that a variable is of type callable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert callable $actual
     */
    public static function assertIsCallable($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_CALLABLE), $message);
    }
    /**
     * Asserts that a variable is of type iterable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert iterable $actual
     */
    public static function assertIsIterable($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_ITERABLE), $message);
    }
    /**
     * Asserts that a variable is not of a given type.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3369
     * @codeCoverageIgnore
     */
    public static function assertNotInternalType(string $expected, $actual, string $message = '') : void
    {
        self::createWarning(\sprintf('assertNotInternalType() is deprecated and will be removed in PHPUnit 9. Refactor your test to use %s() instead.', self::assertInternalTypeReplacement($expected, \true)));
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsType($expected)), $message);
    }
    /**
     * Asserts that a variable is not of type array.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !array $actual
     */
    public static function assertIsNotArray($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_ARRAY)), $message);
    }
    /**
     * Asserts that a variable is not of type bool.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !bool $actual
     */
    public static function assertIsNotBool($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_BOOL)), $message);
    }
    /**
     * Asserts that a variable is not of type float.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !float $actual
     */
    public static function assertIsNotFloat($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_FLOAT)), $message);
    }
    /**
     * Asserts that a variable is not of type int.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !int $actual
     */
    public static function assertIsNotInt($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_INT)), $message);
    }
    /**
     * Asserts that a variable is not of type numeric.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !numeric $actual
     */
    public static function assertIsNotNumeric($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_NUMERIC)), $message);
    }
    /**
     * Asserts that a variable is not of type object.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !object $actual
     */
    public static function assertIsNotObject($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_OBJECT)), $message);
    }
    /**
     * Asserts that a variable is not of type resource.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !resource $actual
     */
    public static function assertIsNotResource($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_RESOURCE)), $message);
    }
    /**
     * Asserts that a variable is not of type string.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !string $actual
     */
    public static function assertIsNotString($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_STRING)), $message);
    }
    /**
     * Asserts that a variable is not of type scalar.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !scalar $actual
     */
    public static function assertIsNotScalar($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_SCALAR)), $message);
    }
    /**
     * Asserts that a variable is not of type callable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !callable $actual
     */
    public static function assertIsNotCallable($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_CALLABLE)), $message);
    }
    /**
     * Asserts that a variable is not of type iterable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !iterable $actual
     */
    public static function assertIsNotIterable($actual, string $message = '') : void
    {
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\IsType(\PHPUnit\Framework\Constraint\IsType::TYPE_ITERABLE)), $message);
    }
    /**
     * Asserts that an attribute is of a given type.
     *
     * @param object|string $classOrObject
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function assertAttributeNotInternalType(string $expected, string $attributeName, $classOrObject, string $message = '') : void
    {
        self::createWarning('assertAttributeNotInternalType() is deprecated and will be removed in PHPUnit 9.');
        static::assertNotInternalType($expected, static::readAttribute($classOrObject, $attributeName), $message);
    }
    /**
     * Asserts that a string matches a given regular expression.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertRegExp(string $pattern, string $string, string $message = '') : void
    {
        static::assertThat($string, new \PHPUnit\Framework\Constraint\RegularExpression($pattern), $message);
    }
    /**
     * Asserts that a string does not match a given regular expression.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertNotRegExp(string $pattern, string $string, string $message = '') : void
    {
        static::assertThat($string, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\RegularExpression($pattern)), $message);
    }
    /**
     * Assert that the size of two arrays (or `Countable` or `Traversable` objects)
     * is the same.
     *
     * @param Countable|iterable $expected
     * @param Countable|iterable $actual
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertSameSize($expected, $actual, string $message = '') : void
    {
        if (!$expected instanceof \Countable && !\is_iterable($expected)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'countable or iterable');
        }
        if (!$actual instanceof \Countable && !\is_iterable($actual)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'countable or iterable');
        }
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\SameSize($expected), $message);
    }
    /**
     * Assert that the size of two arrays (or `Countable` or `Traversable` objects)
     * is not the same.
     *
     * @param Countable|iterable $expected
     * @param Countable|iterable $actual
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertNotSameSize($expected, $actual, string $message = '') : void
    {
        if (!$expected instanceof \Countable && !\is_iterable($expected)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'countable or iterable');
        }
        if (!$actual instanceof \Countable && !\is_iterable($actual)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'countable or iterable');
        }
        static::assertThat($actual, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\SameSize($expected)), $message);
    }
    /**
     * Asserts that a string matches a given format string.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringMatchesFormat(string $format, string $string, string $message = '') : void
    {
        static::assertThat($string, new \PHPUnit\Framework\Constraint\StringMatchesFormatDescription($format), $message);
    }
    /**
     * Asserts that a string does not match a given format string.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringNotMatchesFormat(string $format, string $string, string $message = '') : void
    {
        static::assertThat($string, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\StringMatchesFormatDescription($format)), $message);
    }
    /**
     * Asserts that a string matches a given format file.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringMatchesFormatFile(string $formatFile, string $string, string $message = '') : void
    {
        static::assertFileExists($formatFile, $message);
        static::assertThat($string, new \PHPUnit\Framework\Constraint\StringMatchesFormatDescription(\file_get_contents($formatFile)), $message);
    }
    /**
     * Asserts that a string does not match a given format string.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringNotMatchesFormatFile(string $formatFile, string $string, string $message = '') : void
    {
        static::assertFileExists($formatFile, $message);
        static::assertThat($string, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\StringMatchesFormatDescription(\file_get_contents($formatFile))), $message);
    }
    /**
     * Asserts that a string starts with a given prefix.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringStartsWith(string $prefix, string $string, string $message = '') : void
    {
        static::assertThat($string, new \PHPUnit\Framework\Constraint\StringStartsWith($prefix), $message);
    }
    /**
     * Asserts that a string starts not with a given prefix.
     *
     * @param string $prefix
     * @param string $string
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringStartsNotWith($prefix, $string, string $message = '') : void
    {
        static::assertThat($string, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\StringStartsWith($prefix)), $message);
    }
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringContainsString(string $needle, string $haystack, string $message = '') : void
    {
        $constraint = new \PHPUnit\Framework\Constraint\StringContains($needle, \false);
        static::assertThat($haystack, $constraint, $message);
    }
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringContainsStringIgnoringCase(string $needle, string $haystack, string $message = '') : void
    {
        $constraint = new \PHPUnit\Framework\Constraint\StringContains($needle, \true);
        static::assertThat($haystack, $constraint, $message);
    }
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringNotContainsString(string $needle, string $haystack, string $message = '') : void
    {
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\StringContains($needle));
        static::assertThat($haystack, $constraint, $message);
    }
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringNotContainsStringIgnoringCase(string $needle, string $haystack, string $message = '') : void
    {
        $constraint = new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\StringContains($needle, \true));
        static::assertThat($haystack, $constraint, $message);
    }
    /**
     * Asserts that a string ends with a given suffix.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringEndsWith(string $suffix, string $string, string $message = '') : void
    {
        static::assertThat($string, new \PHPUnit\Framework\Constraint\StringEndsWith($suffix), $message);
    }
    /**
     * Asserts that a string ends not with a given suffix.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertStringEndsNotWith(string $suffix, string $string, string $message = '') : void
    {
        static::assertThat($string, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\StringEndsWith($suffix)), $message);
    }
    /**
     * Asserts that two XML files are equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertXmlFileEqualsXmlFile(string $expectedFile, string $actualFile, string $message = '') : void
    {
        $expected = \PHPUnit\Util\Xml::loadFile($expectedFile);
        $actual = \PHPUnit\Util\Xml::loadFile($actualFile);
        static::assertEquals($expected, $actual, $message);
    }
    /**
     * Asserts that two XML files are not equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertXmlFileNotEqualsXmlFile(string $expectedFile, string $actualFile, string $message = '') : void
    {
        $expected = \PHPUnit\Util\Xml::loadFile($expectedFile);
        $actual = \PHPUnit\Util\Xml::loadFile($actualFile);
        static::assertNotEquals($expected, $actual, $message);
    }
    /**
     * Asserts that two XML documents are equal.
     *
     * @param DOMDocument|string $actualXml
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertXmlStringEqualsXmlFile(string $expectedFile, $actualXml, string $message = '') : void
    {
        $expected = \PHPUnit\Util\Xml::loadFile($expectedFile);
        $actual = \PHPUnit\Util\Xml::load($actualXml);
        static::assertEquals($expected, $actual, $message);
    }
    /**
     * Asserts that two XML documents are not equal.
     *
     * @param DOMDocument|string $actualXml
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertXmlStringNotEqualsXmlFile(string $expectedFile, $actualXml, string $message = '') : void
    {
        $expected = \PHPUnit\Util\Xml::loadFile($expectedFile);
        $actual = \PHPUnit\Util\Xml::load($actualXml);
        static::assertNotEquals($expected, $actual, $message);
    }
    /**
     * Asserts that two XML documents are equal.
     *
     * @param DOMDocument|string $expectedXml
     * @param DOMDocument|string $actualXml
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertXmlStringEqualsXmlString($expectedXml, $actualXml, string $message = '') : void
    {
        $expected = \PHPUnit\Util\Xml::load($expectedXml);
        $actual = \PHPUnit\Util\Xml::load($actualXml);
        static::assertEquals($expected, $actual, $message);
    }
    /**
     * Asserts that two XML documents are not equal.
     *
     * @param DOMDocument|string $expectedXml
     * @param DOMDocument|string $actualXml
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public static function assertXmlStringNotEqualsXmlString($expectedXml, $actualXml, string $message = '') : void
    {
        $expected = \PHPUnit\Util\Xml::load($expectedXml);
        $actual = \PHPUnit\Util\Xml::load($actualXml);
        static::assertNotEquals($expected, $actual, $message);
    }
    /**
     * Asserts that a hierarchy of DOMElements matches.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws AssertionFailedError
     * @throws ExpectationFailedException
     */
    public static function assertEqualXMLStructure(\DOMElement $expectedElement, \DOMElement $actualElement, bool $checkAttributes = \false, string $message = '') : void
    {
        $expectedElement = \PHPUnit\Util\Xml::import($expectedElement);
        $actualElement = \PHPUnit\Util\Xml::import($actualElement);
        static::assertSame($expectedElement->tagName, $actualElement->tagName, $message);
        if ($checkAttributes) {
            static::assertSame($expectedElement->attributes->length, $actualElement->attributes->length, \sprintf('%s%sNumber of attributes on node "%s" does not match', $message, !empty($message) ? "\n" : '', $expectedElement->tagName));
            for ($i = 0; $i < $expectedElement->attributes->length; $i++) {
                $expectedAttribute = $expectedElement->attributes->item($i);
                $actualAttribute = $actualElement->attributes->getNamedItem($expectedAttribute->name);
                \assert($expectedAttribute instanceof \DOMAttr);
                if (!$actualAttribute) {
                    static::fail(\sprintf('%s%sCould not find attribute "%s" on node "%s"', $message, !empty($message) ? "\n" : '', $expectedAttribute->name, $expectedElement->tagName));
                }
            }
        }
        \PHPUnit\Util\Xml::removeCharacterDataNodes($expectedElement);
        \PHPUnit\Util\Xml::removeCharacterDataNodes($actualElement);
        static::assertSame($expectedElement->childNodes->length, $actualElement->childNodes->length, \sprintf('%s%sNumber of child nodes of "%s" differs', $message, !empty($message) ? "\n" : '', $expectedElement->tagName));
        for ($i = 0; $i < $expectedElement->childNodes->length; $i++) {
            static::assertEqualXMLStructure($expectedElement->childNodes->item($i), $actualElement->childNodes->item($i), $checkAttributes, $message);
        }
    }
    /**
     * Evaluates a PHPUnit\Framework\Constraint matcher object.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertThat($value, \PHPUnit\Framework\Constraint\Constraint $constraint, string $message = '') : void
    {
        self::$count += \count($constraint);
        $constraint->evaluate($value, $message);
    }
    /**
     * Asserts that a string is a valid JSON string.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertJson(string $actualJson, string $message = '') : void
    {
        static::assertThat($actualJson, static::isJson(), $message);
    }
    /**
     * Asserts that two given JSON encoded objects or arrays are equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertJsonStringEqualsJsonString(string $expectedJson, string $actualJson, string $message = '') : void
    {
        static::assertJson($expectedJson, $message);
        static::assertJson($actualJson, $message);
        static::assertThat($actualJson, new \PHPUnit\Framework\Constraint\JsonMatches($expectedJson), $message);
    }
    /**
     * Asserts that two given JSON encoded objects or arrays are not equal.
     *
     * @param string $expectedJson
     * @param string $actualJson
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertJsonStringNotEqualsJsonString($expectedJson, $actualJson, string $message = '') : void
    {
        static::assertJson($expectedJson, $message);
        static::assertJson($actualJson, $message);
        static::assertThat($actualJson, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\JsonMatches($expectedJson)), $message);
    }
    /**
     * Asserts that the generated JSON encoded object and the content of the given file are equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertJsonStringEqualsJsonFile(string $expectedFile, string $actualJson, string $message = '') : void
    {
        static::assertFileExists($expectedFile, $message);
        $expectedJson = \file_get_contents($expectedFile);
        static::assertJson($expectedJson, $message);
        static::assertJson($actualJson, $message);
        static::assertThat($actualJson, new \PHPUnit\Framework\Constraint\JsonMatches($expectedJson), $message);
    }
    /**
     * Asserts that the generated JSON encoded object and the content of the given file are not equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertJsonStringNotEqualsJsonFile(string $expectedFile, string $actualJson, string $message = '') : void
    {
        static::assertFileExists($expectedFile, $message);
        $expectedJson = \file_get_contents($expectedFile);
        static::assertJson($expectedJson, $message);
        static::assertJson($actualJson, $message);
        static::assertThat($actualJson, new \PHPUnit\Framework\Constraint\LogicalNot(new \PHPUnit\Framework\Constraint\JsonMatches($expectedJson)), $message);
    }
    /**
     * Asserts that two JSON files are equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertJsonFileEqualsJsonFile(string $expectedFile, string $actualFile, string $message = '') : void
    {
        static::assertFileExists($expectedFile, $message);
        static::assertFileExists($actualFile, $message);
        $actualJson = \file_get_contents($actualFile);
        $expectedJson = \file_get_contents($expectedFile);
        static::assertJson($expectedJson, $message);
        static::assertJson($actualJson, $message);
        $constraintExpected = new \PHPUnit\Framework\Constraint\JsonMatches($expectedJson);
        $constraintActual = new \PHPUnit\Framework\Constraint\JsonMatches($actualJson);
        static::assertThat($expectedJson, $constraintActual, $message);
        static::assertThat($actualJson, $constraintExpected, $message);
    }
    /**
     * Asserts that two JSON files are not equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public static function assertJsonFileNotEqualsJsonFile(string $expectedFile, string $actualFile, string $message = '') : void
    {
        static::assertFileExists($expectedFile, $message);
        static::assertFileExists($actualFile, $message);
        $actualJson = \file_get_contents($actualFile);
        $expectedJson = \file_get_contents($expectedFile);
        static::assertJson($expectedJson, $message);
        static::assertJson($actualJson, $message);
        $constraintExpected = new \PHPUnit\Framework\Constraint\JsonMatches($expectedJson);
        $constraintActual = new \PHPUnit\Framework\Constraint\JsonMatches($actualJson);
        static::assertThat($expectedJson, new \PHPUnit\Framework\Constraint\LogicalNot($constraintActual), $message);
        static::assertThat($actualJson, new \PHPUnit\Framework\Constraint\LogicalNot($constraintExpected), $message);
    }
    /**
     * @throws Exception
     */
    public static function logicalAnd() : \PHPUnit\Framework\Constraint\LogicalAnd
    {
        $constraints = \func_get_args();
        $constraint = new \PHPUnit\Framework\Constraint\LogicalAnd();
        $constraint->setConstraints($constraints);
        return $constraint;
    }
    public static function logicalOr() : \PHPUnit\Framework\Constraint\LogicalOr
    {
        $constraints = \func_get_args();
        $constraint = new \PHPUnit\Framework\Constraint\LogicalOr();
        $constraint->setConstraints($constraints);
        return $constraint;
    }
    public static function logicalNot(\PHPUnit\Framework\Constraint\Constraint $constraint) : \PHPUnit\Framework\Constraint\LogicalNot
    {
        return new \PHPUnit\Framework\Constraint\LogicalNot($constraint);
    }
    public static function logicalXor() : \PHPUnit\Framework\Constraint\LogicalXor
    {
        $constraints = \func_get_args();
        $constraint = new \PHPUnit\Framework\Constraint\LogicalXor();
        $constraint->setConstraints($constraints);
        return $constraint;
    }
    public static function anything() : \PHPUnit\Framework\Constraint\IsAnything
    {
        return new \PHPUnit\Framework\Constraint\IsAnything();
    }
    public static function isTrue() : \PHPUnit\Framework\Constraint\IsTrue
    {
        return new \PHPUnit\Framework\Constraint\IsTrue();
    }
    /**
     * @psalm-template CallbackInput of mixed
     *
     * @psalm-param callable(CallbackInput $callback): bool $callback
     *
     * @psalm-return Callback<CallbackInput>
     */
    public static function callback(callable $callback) : \PHPUnit\Framework\Constraint\Callback
    {
        return new \PHPUnit\Framework\Constraint\Callback($callback);
    }
    public static function isFalse() : \PHPUnit\Framework\Constraint\IsFalse
    {
        return new \PHPUnit\Framework\Constraint\IsFalse();
    }
    public static function isJson() : \PHPUnit\Framework\Constraint\IsJson
    {
        return new \PHPUnit\Framework\Constraint\IsJson();
    }
    public static function isNull() : \PHPUnit\Framework\Constraint\IsNull
    {
        return new \PHPUnit\Framework\Constraint\IsNull();
    }
    public static function isFinite() : \PHPUnit\Framework\Constraint\IsFinite
    {
        return new \PHPUnit\Framework\Constraint\IsFinite();
    }
    public static function isInfinite() : \PHPUnit\Framework\Constraint\IsInfinite
    {
        return new \PHPUnit\Framework\Constraint\IsInfinite();
    }
    public static function isNan() : \PHPUnit\Framework\Constraint\IsNan
    {
        return new \PHPUnit\Framework\Constraint\IsNan();
    }
    /**
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function attribute(\PHPUnit\Framework\Constraint\Constraint $constraint, string $attributeName) : \PHPUnit\Framework\Constraint\Attribute
    {
        self::createWarning('attribute() is deprecated and will be removed in PHPUnit 9.');
        return new \PHPUnit\Framework\Constraint\Attribute($constraint, $attributeName);
    }
    /**
     * @deprecated Use containsEqual() or containsIdentical() instead
     */
    public static function contains($value, bool $checkForObjectIdentity = \true, bool $checkForNonObjectIdentity = \false) : \PHPUnit\Framework\Constraint\TraversableContains
    {
        return new \PHPUnit\Framework\Constraint\TraversableContains($value, $checkForObjectIdentity, $checkForNonObjectIdentity);
    }
    public static function containsEqual($value) : \PHPUnit\Framework\Constraint\TraversableContainsEqual
    {
        return new \PHPUnit\Framework\Constraint\TraversableContainsEqual($value);
    }
    public static function containsIdentical($value) : \PHPUnit\Framework\Constraint\TraversableContainsIdentical
    {
        return new \PHPUnit\Framework\Constraint\TraversableContainsIdentical($value);
    }
    public static function containsOnly(string $type) : \PHPUnit\Framework\Constraint\TraversableContainsOnly
    {
        return new \PHPUnit\Framework\Constraint\TraversableContainsOnly($type);
    }
    public static function containsOnlyInstancesOf(string $className) : \PHPUnit\Framework\Constraint\TraversableContainsOnly
    {
        return new \PHPUnit\Framework\Constraint\TraversableContainsOnly($className, \false);
    }
    /**
     * @param int|string $key
     */
    public static function arrayHasKey($key) : \PHPUnit\Framework\Constraint\ArrayHasKey
    {
        return new \PHPUnit\Framework\Constraint\ArrayHasKey($key);
    }
    public static function equalTo($value, float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = \false, bool $ignoreCase = \false) : \PHPUnit\Framework\Constraint\IsEqual
    {
        return new \PHPUnit\Framework\Constraint\IsEqual($value, $delta, $maxDepth, $canonicalize, $ignoreCase);
    }
    /**
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function attributeEqualTo(string $attributeName, $value, float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = \false, bool $ignoreCase = \false) : \PHPUnit\Framework\Constraint\Attribute
    {
        self::createWarning('attributeEqualTo() is deprecated and will be removed in PHPUnit 9.');
        return static::attribute(static::equalTo($value, $delta, $maxDepth, $canonicalize, $ignoreCase), $attributeName);
    }
    public static function isEmpty() : \PHPUnit\Framework\Constraint\IsEmpty
    {
        return new \PHPUnit\Framework\Constraint\IsEmpty();
    }
    public static function isWritable() : \PHPUnit\Framework\Constraint\IsWritable
    {
        return new \PHPUnit\Framework\Constraint\IsWritable();
    }
    public static function isReadable() : \PHPUnit\Framework\Constraint\IsReadable
    {
        return new \PHPUnit\Framework\Constraint\IsReadable();
    }
    public static function directoryExists() : \PHPUnit\Framework\Constraint\DirectoryExists
    {
        return new \PHPUnit\Framework\Constraint\DirectoryExists();
    }
    public static function fileExists() : \PHPUnit\Framework\Constraint\FileExists
    {
        return new \PHPUnit\Framework\Constraint\FileExists();
    }
    public static function greaterThan($value) : \PHPUnit\Framework\Constraint\GreaterThan
    {
        return new \PHPUnit\Framework\Constraint\GreaterThan($value);
    }
    public static function greaterThanOrEqual($value) : \PHPUnit\Framework\Constraint\LogicalOr
    {
        return static::logicalOr(new \PHPUnit\Framework\Constraint\IsEqual($value), new \PHPUnit\Framework\Constraint\GreaterThan($value));
    }
    public static function classHasAttribute(string $attributeName) : \PHPUnit\Framework\Constraint\ClassHasAttribute
    {
        return new \PHPUnit\Framework\Constraint\ClassHasAttribute($attributeName);
    }
    public static function classHasStaticAttribute(string $attributeName) : \PHPUnit\Framework\Constraint\ClassHasStaticAttribute
    {
        return new \PHPUnit\Framework\Constraint\ClassHasStaticAttribute($attributeName);
    }
    public static function objectHasAttribute($attributeName) : \PHPUnit\Framework\Constraint\ObjectHasAttribute
    {
        return new \PHPUnit\Framework\Constraint\ObjectHasAttribute($attributeName);
    }
    public static function identicalTo($value) : \PHPUnit\Framework\Constraint\IsIdentical
    {
        return new \PHPUnit\Framework\Constraint\IsIdentical($value);
    }
    public static function isInstanceOf(string $className) : \PHPUnit\Framework\Constraint\IsInstanceOf
    {
        return new \PHPUnit\Framework\Constraint\IsInstanceOf($className);
    }
    public static function isType(string $type) : \PHPUnit\Framework\Constraint\IsType
    {
        return new \PHPUnit\Framework\Constraint\IsType($type);
    }
    public static function lessThan($value) : \PHPUnit\Framework\Constraint\LessThan
    {
        return new \PHPUnit\Framework\Constraint\LessThan($value);
    }
    public static function lessThanOrEqual($value) : \PHPUnit\Framework\Constraint\LogicalOr
    {
        return static::logicalOr(new \PHPUnit\Framework\Constraint\IsEqual($value), new \PHPUnit\Framework\Constraint\LessThan($value));
    }
    public static function matchesRegularExpression(string $pattern) : \PHPUnit\Framework\Constraint\RegularExpression
    {
        return new \PHPUnit\Framework\Constraint\RegularExpression($pattern);
    }
    public static function matches(string $string) : \PHPUnit\Framework\Constraint\StringMatchesFormatDescription
    {
        return new \PHPUnit\Framework\Constraint\StringMatchesFormatDescription($string);
    }
    public static function stringStartsWith($prefix) : \PHPUnit\Framework\Constraint\StringStartsWith
    {
        return new \PHPUnit\Framework\Constraint\StringStartsWith($prefix);
    }
    public static function stringContains(string $string, bool $case = \true) : \PHPUnit\Framework\Constraint\StringContains
    {
        return new \PHPUnit\Framework\Constraint\StringContains($string, $case);
    }
    public static function stringEndsWith(string $suffix) : \PHPUnit\Framework\Constraint\StringEndsWith
    {
        return new \PHPUnit\Framework\Constraint\StringEndsWith($suffix);
    }
    public static function countOf(int $count) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\count
    {
        return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\count($count);
    }
    /**
     * Fails a test with the given message.
     *
     * @throws AssertionFailedError
     *
     * @psalm-return never-return
     */
    public static function fail(string $message = '') : void
    {
        self::$count++;
        throw new \PHPUnit\Framework\AssertionFailedError($message);
    }
    /**
     * Returns the value of an attribute of a class or an object.
     * This also works for attributes that are declared protected or private.
     *
     * @param object|string $classOrObject
     *
     * @throws Exception
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function readAttribute($classOrObject, string $attributeName)
    {
        self::createWarning('readAttribute() is deprecated and will be removed in PHPUnit 9.');
        if (!self::isValidClassAttributeName($attributeName)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'valid attribute name');
        }
        if (\is_string($classOrObject)) {
            if (!\class_exists($classOrObject)) {
                throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'class name');
            }
            return static::getStaticAttribute($classOrObject, $attributeName);
        }
        if (\is_object($classOrObject)) {
            return static::getObjectAttribute($classOrObject, $attributeName);
        }
        throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'class name or object');
    }
    /**
     * Returns the value of a static attribute.
     * This also works for attributes that are declared protected or private.
     *
     * @throws Exception
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function getStaticAttribute(string $className, string $attributeName)
    {
        self::createWarning('getStaticAttribute() is deprecated and will be removed in PHPUnit 9.');
        if (!\class_exists($className)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'class name');
        }
        if (!self::isValidClassAttributeName($attributeName)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'valid attribute name');
        }
        try {
            $class = new \ReflectionClass($className);
            // @codeCoverageIgnoreStart
        } catch (\ReflectionException $e) {
            throw new \PHPUnit\Framework\Exception($e->getMessage(), (int) $e->getCode(), $e);
        }
        // @codeCoverageIgnoreEnd
        while ($class) {
            $attributes = $class->getStaticProperties();
            if (\array_key_exists($attributeName, $attributes)) {
                return $attributes[$attributeName];
            }
            $class = $class->getParentClass();
        }
        throw new \PHPUnit\Framework\Exception(\sprintf('Attribute "%s" not found in class.', $attributeName));
    }
    /**
     * Returns the value of an object's attribute.
     * This also works for attributes that are declared protected or private.
     *
     * @param object $object
     *
     * @throws Exception
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     * @codeCoverageIgnore
     */
    public static function getObjectAttribute($object, string $attributeName)
    {
        self::createWarning('getObjectAttribute() is deprecated and will be removed in PHPUnit 9.');
        if (!\is_object($object)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(1, 'object');
        }
        if (!self::isValidClassAttributeName($attributeName)) {
            throw \PHPUnit\Framework\InvalidArgumentException::create(2, 'valid attribute name');
        }
        $reflector = new \ReflectionObject($object);
        do {
            try {
                $attribute = $reflector->getProperty($attributeName);
                if (!$attribute || $attribute->isPublic()) {
                    return $object->{$attributeName};
                }
                $attribute->setAccessible(\true);
                $value = $attribute->getValue($object);
                $attribute->setAccessible(\false);
                return $value;
            } catch (\ReflectionException $e) {
            }
        } while ($reflector = $reflector->getParentClass());
        throw new \PHPUnit\Framework\Exception(\sprintf('Attribute "%s" not found in object.', $attributeName));
    }
    /**
     * Mark the test as incomplete.
     *
     * @throws IncompleteTestError
     *
     * @psalm-return never-return
     */
    public static function markTestIncomplete(string $message = '') : void
    {
        throw new \PHPUnit\Framework\IncompleteTestError($message);
    }
    /**
     * Mark the test as skipped.
     *
     * @throws SkippedTestError
     * @throws SyntheticSkippedError
     *
     * @psalm-return never-return
     */
    public static function markTestSkipped(string $message = '') : void
    {
        if ($hint = self::detectLocationHint($message)) {
            $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);
            \array_unshift($trace, $hint);
            throw new \PHPUnit\Framework\SyntheticSkippedError($hint['message'], 0, $hint['file'], (int) $hint['line'], $trace);
        }
        throw new \PHPUnit\Framework\SkippedTestError($message);
    }
    /**
     * Return the current assertion count.
     */
    public static function getCount() : int
    {
        return self::$count;
    }
    /**
     * Reset the assertion counter.
     */
    public static function resetCount() : void
    {
        self::$count = 0;
    }
    private static function detectLocationHint(string $message) : ?array
    {
        $hint = null;
        $lines = \preg_split('/\\r\\n|\\r|\\n/', $message);
        while (\strpos($lines[0], '__OFFSET') !== \false) {
            $offset = \explode('=', \array_shift($lines));
            if ($offset[0] === '__OFFSET_FILE') {
                $hint['file'] = $offset[1];
            }
            if ($offset[0] === '__OFFSET_LINE') {
                $hint['line'] = $offset[1];
            }
        }
        if ($hint) {
            $hint['message'] = \implode(\PHP_EOL, $lines);
        }
        return $hint;
    }
    private static function isValidObjectAttributeName(string $attributeName) : bool
    {
        return (bool) \preg_match('/[^\\x00-\\x1f\\x7f-\\x9f]+/', $attributeName);
    }
    private static function isValidClassAttributeName(string $attributeName) : bool
    {
        return (bool) \preg_match('/[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*/', $attributeName);
    }
    /**
     * @codeCoverageIgnore
     */
    private static function createWarning(string $warning) : void
    {
        foreach (\debug_backtrace() as $step) {
            if (isset($step['object']) && $step['object'] instanceof \PHPUnit\Framework\TestCase) {
                \assert($step['object'] instanceof \PHPUnit\Framework\TestCase);
                $step['object']->addWarning($warning);
                break;
            }
        }
    }
    /**
     * @throws Exception
     */
    private static function assertInternalTypeReplacement(string $type, bool $not) : string
    {
        switch ($type) {
            case 'numeric':
                return 'assertIs' . ($not ? 'Not' : '') . 'Numeric';
            case 'integer':
            case 'int':
                return 'assertIs' . ($not ? 'Not' : '') . 'Int';
            case 'double':
            case 'float':
            case 'real':
                return 'assertIs' . ($not ? 'Not' : '') . 'Float';
            case 'string':
                return 'assertIs' . ($not ? 'Not' : '') . 'String';
            case 'boolean':
            case 'bool':
                return 'assertIs' . ($not ? 'Not' : '') . 'Bool';
            case 'null':
                return 'assert' . ($not ? 'Not' : '') . 'Null';
            case 'array':
                return 'assertIs' . ($not ? 'Not' : '') . 'Array';
            case 'object':
                return 'assertIs' . ($not ? 'Not' : '') . 'Object';
            case 'resource':
                return 'assertIs' . ($not ? 'Not' : '') . 'Resource';
            case 'scalar':
                return 'assertIs' . ($not ? 'Not' : '') . 'Scalar';
            case 'callable':
                return 'assertIs' . ($not ? 'Not' : '') . 'Callable';
            case 'iterable':
                return 'assertIs' . ($not ? 'Not' : '') . 'Iterable';
        }
        throw new \PHPUnit\Framework\Exception(\sprintf('"%s" is not a type supported by assertInternalType() / assertNotInternalType()', $type));
    }
}
