<?php

declare (strict_types=1);
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies;

/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Constraint\ArrayHasKey;
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
use PHPUnit\Framework\Constraint\LessThan;
use PHPUnit\Framework\Constraint\LogicalAnd;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\LogicalOr;
use PHPUnit\Framework\Constraint\LogicalXor;
use PHPUnit\Framework\Constraint\ObjectHasAttribute;
use PHPUnit\Framework\Constraint\RegularExpression;
use PHPUnit\Framework\Constraint\StringContains;
use PHPUnit\Framework\Constraint\StringEndsWith;
use PHPUnit\Framework\Constraint\StringMatchesFormatDescription;
use PHPUnit\Framework\Constraint\StringStartsWith;
use PHPUnit\Framework\Constraint\TraversableContains;
use PHPUnit\Framework\Constraint\TraversableContainsEqual;
use PHPUnit\Framework\Constraint\TraversableContainsIdentical;
use PHPUnit\Framework\Constraint\TraversableContainsOnly;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Rule\AnyInvokedCount as AnyInvokedCountMatcher;
use PHPUnit\Framework\MockObject\Rule\InvokedAtIndex as InvokedAtIndexMatcher;
use PHPUnit\Framework\MockObject\Rule\InvokedAtLeastCount as InvokedAtLeastCountMatcher;
use PHPUnit\Framework\MockObject\Rule\InvokedAtLeastOnce as InvokedAtLeastOnceMatcher;
use PHPUnit\Framework\MockObject\Rule\InvokedAtMostCount as InvokedAtMostCountMatcher;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use PHPUnit\Framework\MockObject\Stub\ConsecutiveCalls as ConsecutiveCallsStub;
use PHPUnit\Framework\MockObject\Stub\Exception as ExceptionStub;
use PHPUnit\Framework\MockObject\Stub\ReturnArgument as ReturnArgumentStub;
use PHPUnit\Framework\MockObject\Stub\ReturnCallback as ReturnCallbackStub;
use PHPUnit\Framework\MockObject\Stub\ReturnSelf as ReturnSelfStub;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;
use PHPUnit\Framework\MockObject\Stub\ReturnValueMap as ReturnValueMapStub;
if (!\function_exists('PHPUnit\\Framework\\assertArrayHasKey')) {
    /**
     * Asserts that an array has a specified key.
     *
     * @param int|string        $key
     * @param array|ArrayAccess $array
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertArrayHasKey
     */
    function assertArrayHasKey($key, $array, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertArrayHasKey(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertArraySubset')) {
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
     * @see Assert::assertArraySubset
     */
    function assertArraySubset($subset, $array, bool $checkForObjectIdentity = \false, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertArraySubset(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertArrayNotHasKey')) {
    /**
     * Asserts that an array does not have a specified key.
     *
     * @param int|string        $key
     * @param array|ArrayAccess $array
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertArrayNotHasKey
     */
    function assertArrayNotHasKey($key, $array, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertArrayNotHasKey(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertContains')) {
    /**
     * Asserts that a haystack contains a needle.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertContains
     */
    function assertContains($needle, $haystack, string $message = '', bool $ignoreCase = \false, bool $checkForObjectIdentity = \true, bool $checkForNonObjectIdentity = \false) : void
    {
        \PHPUnit\Framework\Assert::assertContains(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertContainsEquals')) {
    function assertContainsEquals($needle, iterable $haystack, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertContainsEquals(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeContains')) {
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
     *
     * @see Assert::assertAttributeContains
     */
    function assertAttributeContains($needle, string $haystackAttributeName, $haystackClassOrObject, string $message = '', bool $ignoreCase = \false, bool $checkForObjectIdentity = \true, bool $checkForNonObjectIdentity = \false) : void
    {
        \PHPUnit\Framework\Assert::assertAttributeContains(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotContains')) {
    /**
     * Asserts that a haystack does not contain a needle.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertNotContains
     */
    function assertNotContains($needle, $haystack, string $message = '', bool $ignoreCase = \false, bool $checkForObjectIdentity = \true, bool $checkForNonObjectIdentity = \false) : void
    {
        \PHPUnit\Framework\Assert::assertNotContains(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotContainsEquals')) {
    function assertNotContainsEquals($needle, iterable $haystack, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotContainsEquals(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeNotContains')) {
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
     *
     * @see Assert::assertAttributeNotContains
     */
    function assertAttributeNotContains($needle, string $haystackAttributeName, $haystackClassOrObject, string $message = '', bool $ignoreCase = \false, bool $checkForObjectIdentity = \true, bool $checkForNonObjectIdentity = \false) : void
    {
        \PHPUnit\Framework\Assert::assertAttributeNotContains(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertContainsOnly')) {
    /**
     * Asserts that a haystack contains only values of a given type.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertContainsOnly
     */
    function assertContainsOnly(string $type, iterable $haystack, ?bool $isNativeType = null, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertContainsOnly(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertContainsOnlyInstancesOf')) {
    /**
     * Asserts that a haystack contains only instances of a given class name.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertContainsOnlyInstancesOf
     */
    function assertContainsOnlyInstancesOf(string $className, iterable $haystack, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertContainsOnlyInstancesOf(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeContainsOnly')) {
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
     *
     * @see Assert::assertAttributeContainsOnly
     */
    function assertAttributeContainsOnly(string $type, string $haystackAttributeName, $haystackClassOrObject, ?bool $isNativeType = null, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeContainsOnly(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotContainsOnly')) {
    /**
     * Asserts that a haystack does not contain only values of a given type.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertNotContainsOnly
     */
    function assertNotContainsOnly(string $type, iterable $haystack, ?bool $isNativeType = null, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotContainsOnly(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeNotContainsOnly')) {
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
     *
     * @see Assert::assertAttributeNotContainsOnly
     */
    function assertAttributeNotContainsOnly(string $type, string $haystackAttributeName, $haystackClassOrObject, ?bool $isNativeType = null, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeNotContainsOnly(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertCount')) {
    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param Countable|iterable $haystack
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertCount
     */
    function assertCount(int $expectedCount, $haystack, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertCount(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeCount')) {
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
     *
     * @see Assert::assertAttributeCount
     */
    function assertAttributeCount(int $expectedCount, string $haystackAttributeName, $haystackClassOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeCount(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotCount')) {
    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param Countable|iterable $haystack
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertNotCount
     */
    function assertNotCount(int $expectedCount, $haystack, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotCount(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeNotCount')) {
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
     *
     * @see Assert::assertAttributeNotCount
     */
    function assertAttributeNotCount(int $expectedCount, string $haystackAttributeName, $haystackClassOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeNotCount(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertEquals')) {
    /**
     * Asserts that two variables are equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertEquals
     */
    function assertEquals($expected, $actual, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        \PHPUnit\Framework\Assert::assertEquals(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertEqualsCanonicalizing')) {
    /**
     * Asserts that two variables are equal (canonicalizing).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertEqualsCanonicalizing
     */
    function assertEqualsCanonicalizing($expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertEqualsCanonicalizing(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertEqualsIgnoringCase')) {
    /**
     * Asserts that two variables are equal (ignoring case).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertEqualsIgnoringCase
     */
    function assertEqualsIgnoringCase($expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertEqualsIgnoringCase(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertEqualsWithDelta')) {
    /**
     * Asserts that two variables are equal (with delta).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertEqualsWithDelta
     */
    function assertEqualsWithDelta($expected, $actual, float $delta, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertEqualsWithDelta(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeEquals')) {
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
     *
     * @see Assert::assertAttributeEquals
     */
    function assertAttributeEquals($expected, string $actualAttributeName, $actualClassOrObject, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        \PHPUnit\Framework\Assert::assertAttributeEquals(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotEquals')) {
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
     *
     * @see Assert::assertNotEquals
     */
    function assertNotEquals($expected, $actual, string $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = \false, $ignoreCase = \false) : void
    {
        \PHPUnit\Framework\Assert::assertNotEquals(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotEqualsCanonicalizing')) {
    /**
     * Asserts that two variables are not equal (canonicalizing).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertNotEqualsCanonicalizing
     */
    function assertNotEqualsCanonicalizing($expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotEqualsCanonicalizing(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotEqualsIgnoringCase')) {
    /**
     * Asserts that two variables are not equal (ignoring case).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertNotEqualsIgnoringCase
     */
    function assertNotEqualsIgnoringCase($expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotEqualsIgnoringCase(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotEqualsWithDelta')) {
    /**
     * Asserts that two variables are not equal (with delta).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertNotEqualsWithDelta
     */
    function assertNotEqualsWithDelta($expected, $actual, float $delta, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotEqualsWithDelta(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeNotEquals')) {
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
     *
     * @see Assert::assertAttributeNotEquals
     */
    function assertAttributeNotEquals($expected, string $actualAttributeName, $actualClassOrObject, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        \PHPUnit\Framework\Assert::assertAttributeNotEquals(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertEmpty')) {
    /**
     * Asserts that a variable is empty.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert empty $actual
     *
     * @see Assert::assertEmpty
     */
    function assertEmpty($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertEmpty(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeEmpty')) {
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
     *
     * @see Assert::assertAttributeEmpty
     */
    function assertAttributeEmpty(string $haystackAttributeName, $haystackClassOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeEmpty(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotEmpty')) {
    /**
     * Asserts that a variable is not empty.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !empty $actual
     *
     * @see Assert::assertNotEmpty
     */
    function assertNotEmpty($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotEmpty(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeNotEmpty')) {
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
     *
     * @see Assert::assertAttributeNotEmpty
     */
    function assertAttributeNotEmpty(string $haystackAttributeName, $haystackClassOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeNotEmpty(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertGreaterThan')) {
    /**
     * Asserts that a value is greater than another value.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertGreaterThan
     */
    function assertGreaterThan($expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertGreaterThan(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeGreaterThan')) {
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
     *
     * @see Assert::assertAttributeGreaterThan
     */
    function assertAttributeGreaterThan($expected, string $actualAttributeName, $actualClassOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeGreaterThan(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertGreaterThanOrEqual')) {
    /**
     * Asserts that a value is greater than or equal to another value.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertGreaterThanOrEqual
     */
    function assertGreaterThanOrEqual($expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertGreaterThanOrEqual(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeGreaterThanOrEqual')) {
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
     *
     * @see Assert::assertAttributeGreaterThanOrEqual
     */
    function assertAttributeGreaterThanOrEqual($expected, string $actualAttributeName, $actualClassOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeGreaterThanOrEqual(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertLessThan')) {
    /**
     * Asserts that a value is smaller than another value.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertLessThan
     */
    function assertLessThan($expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertLessThan(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeLessThan')) {
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
     *
     * @see Assert::assertAttributeLessThan
     */
    function assertAttributeLessThan($expected, string $actualAttributeName, $actualClassOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeLessThan(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertLessThanOrEqual')) {
    /**
     * Asserts that a value is smaller than or equal to another value.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertLessThanOrEqual
     */
    function assertLessThanOrEqual($expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertLessThanOrEqual(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeLessThanOrEqual')) {
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
     *
     * @see Assert::assertAttributeLessThanOrEqual
     */
    function assertAttributeLessThanOrEqual($expected, string $actualAttributeName, $actualClassOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeLessThanOrEqual(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFileEquals')) {
    /**
     * Asserts that the contents of one file is equal to the contents of another
     * file.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertFileEquals
     */
    function assertFileEquals(string $expected, string $actual, string $message = '', bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        \PHPUnit\Framework\Assert::assertFileEquals(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFileEqualsCanonicalizing')) {
    /**
     * Asserts that the contents of one file is equal to the contents of another
     * file (canonicalizing).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertFileEqualsCanonicalizing
     */
    function assertFileEqualsCanonicalizing(string $expected, string $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertFileEqualsCanonicalizing(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFileEqualsIgnoringCase')) {
    /**
     * Asserts that the contents of one file is equal to the contents of another
     * file (ignoring case).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertFileEqualsIgnoringCase
     */
    function assertFileEqualsIgnoringCase(string $expected, string $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertFileEqualsIgnoringCase(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFileNotEquals')) {
    /**
     * Asserts that the contents of one file is not equal to the contents of
     * another file.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertFileNotEquals
     */
    function assertFileNotEquals(string $expected, string $actual, string $message = '', bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        \PHPUnit\Framework\Assert::assertFileNotEquals(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFileNotEqualsCanonicalizing')) {
    /**
     * Asserts that the contents of one file is not equal to the contents of another
     * file (canonicalizing).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertFileNotEqualsCanonicalizing
     */
    function assertFileNotEqualsCanonicalizing(string $expected, string $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertFileNotEqualsCanonicalizing(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFileNotEqualsIgnoringCase')) {
    /**
     * Asserts that the contents of one file is not equal to the contents of another
     * file (ignoring case).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertFileNotEqualsIgnoringCase
     */
    function assertFileNotEqualsIgnoringCase(string $expected, string $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertFileNotEqualsIgnoringCase(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringEqualsFile')) {
    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringEqualsFile
     */
    function assertStringEqualsFile(string $expectedFile, string $actualString, string $message = '', bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        \PHPUnit\Framework\Assert::assertStringEqualsFile(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringEqualsFileCanonicalizing')) {
    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file (canonicalizing).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringEqualsFileCanonicalizing
     */
    function assertStringEqualsFileCanonicalizing(string $expectedFile, string $actualString, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringEqualsFileCanonicalizing(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringEqualsFileIgnoringCase')) {
    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file (ignoring case).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringEqualsFileIgnoringCase
     */
    function assertStringEqualsFileIgnoringCase(string $expectedFile, string $actualString, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringEqualsFileIgnoringCase(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringNotEqualsFile')) {
    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringNotEqualsFile
     */
    function assertStringNotEqualsFile(string $expectedFile, string $actualString, string $message = '', bool $canonicalize = \false, bool $ignoreCase = \false) : void
    {
        \PHPUnit\Framework\Assert::assertStringNotEqualsFile(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringNotEqualsFileCanonicalizing')) {
    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file (canonicalizing).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringNotEqualsFileCanonicalizing
     */
    function assertStringNotEqualsFileCanonicalizing(string $expectedFile, string $actualString, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringNotEqualsFileCanonicalizing(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringNotEqualsFileIgnoringCase')) {
    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file (ignoring case).
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringNotEqualsFileIgnoringCase
     */
    function assertStringNotEqualsFileIgnoringCase(string $expectedFile, string $actualString, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringNotEqualsFileIgnoringCase(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsReadable')) {
    /**
     * Asserts that a file/dir is readable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertIsReadable
     */
    function assertIsReadable(string $filename, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsReadable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotIsReadable')) {
    /**
     * Asserts that a file/dir exists and is not readable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertNotIsReadable
     */
    function assertNotIsReadable(string $filename, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotIsReadable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsWritable')) {
    /**
     * Asserts that a file/dir exists and is writable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertIsWritable
     */
    function assertIsWritable(string $filename, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsWritable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotIsWritable')) {
    /**
     * Asserts that a file/dir exists and is not writable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertNotIsWritable
     */
    function assertNotIsWritable(string $filename, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotIsWritable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertDirectoryExists')) {
    /**
     * Asserts that a directory exists.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertDirectoryExists
     */
    function assertDirectoryExists(string $directory, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertDirectoryExists(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertDirectoryNotExists')) {
    /**
     * Asserts that a directory does not exist.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertDirectoryNotExists
     */
    function assertDirectoryNotExists(string $directory, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertDirectoryNotExists(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertDirectoryIsReadable')) {
    /**
     * Asserts that a directory exists and is readable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertDirectoryIsReadable
     */
    function assertDirectoryIsReadable(string $directory, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertDirectoryIsReadable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertDirectoryNotIsReadable')) {
    /**
     * Asserts that a directory exists and is not readable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertDirectoryNotIsReadable
     */
    function assertDirectoryNotIsReadable(string $directory, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertDirectoryNotIsReadable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertDirectoryIsWritable')) {
    /**
     * Asserts that a directory exists and is writable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertDirectoryIsWritable
     */
    function assertDirectoryIsWritable(string $directory, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertDirectoryIsWritable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertDirectoryNotIsWritable')) {
    /**
     * Asserts that a directory exists and is not writable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertDirectoryNotIsWritable
     */
    function assertDirectoryNotIsWritable(string $directory, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertDirectoryNotIsWritable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFileExists')) {
    /**
     * Asserts that a file exists.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertFileExists
     */
    function assertFileExists(string $filename, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertFileExists(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFileNotExists')) {
    /**
     * Asserts that a file does not exist.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertFileNotExists
     */
    function assertFileNotExists(string $filename, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertFileNotExists(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFileIsReadable')) {
    /**
     * Asserts that a file exists and is readable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertFileIsReadable
     */
    function assertFileIsReadable(string $file, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertFileIsReadable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFileNotIsReadable')) {
    /**
     * Asserts that a file exists and is not readable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertFileNotIsReadable
     */
    function assertFileNotIsReadable(string $file, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertFileNotIsReadable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFileIsWritable')) {
    /**
     * Asserts that a file exists and is writable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertFileIsWritable
     */
    function assertFileIsWritable(string $file, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertFileIsWritable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFileNotIsWritable')) {
    /**
     * Asserts that a file exists and is not writable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertFileNotIsWritable
     */
    function assertFileNotIsWritable(string $file, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertFileNotIsWritable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertTrue')) {
    /**
     * Asserts that a condition is true.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert true $condition
     *
     * @see Assert::assertTrue
     */
    function assertTrue($condition, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertTrue(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotTrue')) {
    /**
     * Asserts that a condition is not true.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !true $condition
     *
     * @see Assert::assertNotTrue
     */
    function assertNotTrue($condition, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotTrue(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFalse')) {
    /**
     * Asserts that a condition is false.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert false $condition
     *
     * @see Assert::assertFalse
     */
    function assertFalse($condition, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertFalse(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotFalse')) {
    /**
     * Asserts that a condition is not false.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !false $condition
     *
     * @see Assert::assertNotFalse
     */
    function assertNotFalse($condition, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotFalse(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNull')) {
    /**
     * Asserts that a variable is null.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert null $actual
     *
     * @see Assert::assertNull
     */
    function assertNull($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNull(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotNull')) {
    /**
     * Asserts that a variable is not null.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !null $actual
     *
     * @see Assert::assertNotNull
     */
    function assertNotNull($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotNull(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertFinite')) {
    /**
     * Asserts that a variable is finite.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertFinite
     */
    function assertFinite($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertFinite(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertInfinite')) {
    /**
     * Asserts that a variable is infinite.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertInfinite
     */
    function assertInfinite($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertInfinite(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNan')) {
    /**
     * Asserts that a variable is nan.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertNan
     */
    function assertNan($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNan(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertClassHasAttribute')) {
    /**
     * Asserts that a class has a specified attribute.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertClassHasAttribute
     */
    function assertClassHasAttribute(string $attributeName, string $className, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertClassHasAttribute(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertClassNotHasAttribute')) {
    /**
     * Asserts that a class does not have a specified attribute.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertClassNotHasAttribute
     */
    function assertClassNotHasAttribute(string $attributeName, string $className, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertClassNotHasAttribute(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertClassHasStaticAttribute')) {
    /**
     * Asserts that a class has a specified static attribute.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertClassHasStaticAttribute
     */
    function assertClassHasStaticAttribute(string $attributeName, string $className, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertClassHasStaticAttribute(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertClassNotHasStaticAttribute')) {
    /**
     * Asserts that a class does not have a specified static attribute.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertClassNotHasStaticAttribute
     */
    function assertClassNotHasStaticAttribute(string $attributeName, string $className, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertClassNotHasStaticAttribute(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertObjectHasAttribute')) {
    /**
     * Asserts that an object has a specified attribute.
     *
     * @param object $object
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertObjectHasAttribute
     */
    function assertObjectHasAttribute(string $attributeName, $object, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertObjectHasAttribute(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertObjectNotHasAttribute')) {
    /**
     * Asserts that an object does not have a specified attribute.
     *
     * @param object $object
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertObjectNotHasAttribute
     */
    function assertObjectNotHasAttribute(string $attributeName, $object, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertObjectNotHasAttribute(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertSame')) {
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
     *
     * @see Assert::assertSame
     */
    function assertSame($expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertSame(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeSame')) {
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
     *
     * @see Assert::assertAttributeSame
     */
    function assertAttributeSame($expected, string $actualAttributeName, $actualClassOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeSame(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotSame')) {
    /**
     * Asserts that two variables do not have the same type and value.
     * Used on objects, it asserts that two variables do not reference
     * the same object.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertNotSame
     */
    function assertNotSame($expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotSame(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeNotSame')) {
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
     *
     * @see Assert::assertAttributeNotSame
     */
    function assertAttributeNotSame($expected, string $actualAttributeName, $actualClassOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeNotSame(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertInstanceOf')) {
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
     *
     * @see Assert::assertInstanceOf
     */
    function assertInstanceOf(string $expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertInstanceOf(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeInstanceOf')) {
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
     *
     * @see Assert::assertAttributeInstanceOf
     */
    function assertAttributeInstanceOf(string $expected, string $attributeName, $classOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeInstanceOf(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotInstanceOf')) {
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
     *
     * @see Assert::assertNotInstanceOf
     */
    function assertNotInstanceOf(string $expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotInstanceOf(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeNotInstanceOf')) {
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
     *
     * @see Assert::assertAttributeNotInstanceOf
     */
    function assertAttributeNotInstanceOf(string $expected, string $attributeName, $classOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeNotInstanceOf(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertInternalType')) {
    /**
     * Asserts that a variable is of a given type.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3369
     * @codeCoverageIgnore
     *
     * @see Assert::assertInternalType
     */
    function assertInternalType(string $expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertInternalType(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeInternalType')) {
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
     * @see Assert::assertAttributeInternalType
     */
    function assertAttributeInternalType(string $expected, string $attributeName, $classOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeInternalType(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsArray')) {
    /**
     * Asserts that a variable is of type array.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert array $actual
     *
     * @see Assert::assertIsArray
     */
    function assertIsArray($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsArray(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsBool')) {
    /**
     * Asserts that a variable is of type bool.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert bool $actual
     *
     * @see Assert::assertIsBool
     */
    function assertIsBool($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsBool(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsFloat')) {
    /**
     * Asserts that a variable is of type float.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert float $actual
     *
     * @see Assert::assertIsFloat
     */
    function assertIsFloat($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsFloat(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsInt')) {
    /**
     * Asserts that a variable is of type int.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert int $actual
     *
     * @see Assert::assertIsInt
     */
    function assertIsInt($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsInt(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsNumeric')) {
    /**
     * Asserts that a variable is of type numeric.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert numeric $actual
     *
     * @see Assert::assertIsNumeric
     */
    function assertIsNumeric($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsNumeric(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsObject')) {
    /**
     * Asserts that a variable is of type object.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert object $actual
     *
     * @see Assert::assertIsObject
     */
    function assertIsObject($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsObject(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsResource')) {
    /**
     * Asserts that a variable is of type resource.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert resource $actual
     *
     * @see Assert::assertIsResource
     */
    function assertIsResource($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsResource(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsString')) {
    /**
     * Asserts that a variable is of type string.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert string $actual
     *
     * @see Assert::assertIsString
     */
    function assertIsString($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsString(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsScalar')) {
    /**
     * Asserts that a variable is of type scalar.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert scalar $actual
     *
     * @see Assert::assertIsScalar
     */
    function assertIsScalar($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsScalar(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsCallable')) {
    /**
     * Asserts that a variable is of type callable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert callable $actual
     *
     * @see Assert::assertIsCallable
     */
    function assertIsCallable($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsCallable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsIterable')) {
    /**
     * Asserts that a variable is of type iterable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert iterable $actual
     *
     * @see Assert::assertIsIterable
     */
    function assertIsIterable($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsIterable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotInternalType')) {
    /**
     * Asserts that a variable is not of a given type.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3369
     * @codeCoverageIgnore
     *
     * @see Assert::assertNotInternalType
     */
    function assertNotInternalType(string $expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotInternalType(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsNotArray')) {
    /**
     * Asserts that a variable is not of type array.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !array $actual
     *
     * @see Assert::assertIsNotArray
     */
    function assertIsNotArray($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsNotArray(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsNotBool')) {
    /**
     * Asserts that a variable is not of type bool.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !bool $actual
     *
     * @see Assert::assertIsNotBool
     */
    function assertIsNotBool($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsNotBool(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsNotFloat')) {
    /**
     * Asserts that a variable is not of type float.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !float $actual
     *
     * @see Assert::assertIsNotFloat
     */
    function assertIsNotFloat($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsNotFloat(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsNotInt')) {
    /**
     * Asserts that a variable is not of type int.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !int $actual
     *
     * @see Assert::assertIsNotInt
     */
    function assertIsNotInt($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsNotInt(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsNotNumeric')) {
    /**
     * Asserts that a variable is not of type numeric.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !numeric $actual
     *
     * @see Assert::assertIsNotNumeric
     */
    function assertIsNotNumeric($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsNotNumeric(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsNotObject')) {
    /**
     * Asserts that a variable is not of type object.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !object $actual
     *
     * @see Assert::assertIsNotObject
     */
    function assertIsNotObject($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsNotObject(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsNotResource')) {
    /**
     * Asserts that a variable is not of type resource.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !resource $actual
     *
     * @see Assert::assertIsNotResource
     */
    function assertIsNotResource($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsNotResource(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsNotString')) {
    /**
     * Asserts that a variable is not of type string.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !string $actual
     *
     * @see Assert::assertIsNotString
     */
    function assertIsNotString($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsNotString(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsNotScalar')) {
    /**
     * Asserts that a variable is not of type scalar.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !scalar $actual
     *
     * @see Assert::assertIsNotScalar
     */
    function assertIsNotScalar($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsNotScalar(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsNotCallable')) {
    /**
     * Asserts that a variable is not of type callable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !callable $actual
     *
     * @see Assert::assertIsNotCallable
     */
    function assertIsNotCallable($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsNotCallable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertIsNotIterable')) {
    /**
     * Asserts that a variable is not of type iterable.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @psalm-assert !iterable $actual
     *
     * @see Assert::assertIsNotIterable
     */
    function assertIsNotIterable($actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertIsNotIterable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertAttributeNotInternalType')) {
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
     * @see Assert::assertAttributeNotInternalType
     */
    function assertAttributeNotInternalType(string $expected, string $attributeName, $classOrObject, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertAttributeNotInternalType(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertRegExp')) {
    /**
     * Asserts that a string matches a given regular expression.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertRegExp
     */
    function assertRegExp(string $pattern, string $string, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertRegExp(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotRegExp')) {
    /**
     * Asserts that a string does not match a given regular expression.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertNotRegExp
     */
    function assertNotRegExp(string $pattern, string $string, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotRegExp(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertSameSize')) {
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
     *
     * @see Assert::assertSameSize
     */
    function assertSameSize($expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertSameSize(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertNotSameSize')) {
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
     *
     * @see Assert::assertNotSameSize
     */
    function assertNotSameSize($expected, $actual, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertNotSameSize(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringMatchesFormat')) {
    /**
     * Asserts that a string matches a given format string.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringMatchesFormat
     */
    function assertStringMatchesFormat(string $format, string $string, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringMatchesFormat(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringNotMatchesFormat')) {
    /**
     * Asserts that a string does not match a given format string.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringNotMatchesFormat
     */
    function assertStringNotMatchesFormat(string $format, string $string, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringNotMatchesFormat(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringMatchesFormatFile')) {
    /**
     * Asserts that a string matches a given format file.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringMatchesFormatFile
     */
    function assertStringMatchesFormatFile(string $formatFile, string $string, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringMatchesFormatFile(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringNotMatchesFormatFile')) {
    /**
     * Asserts that a string does not match a given format string.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringNotMatchesFormatFile
     */
    function assertStringNotMatchesFormatFile(string $formatFile, string $string, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringNotMatchesFormatFile(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringStartsWith')) {
    /**
     * Asserts that a string starts with a given prefix.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringStartsWith
     */
    function assertStringStartsWith(string $prefix, string $string, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringStartsWith(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringStartsNotWith')) {
    /**
     * Asserts that a string starts not with a given prefix.
     *
     * @param string $prefix
     * @param string $string
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringStartsNotWith
     */
    function assertStringStartsNotWith($prefix, $string, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringStartsNotWith(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringContainsString')) {
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringContainsString
     */
    function assertStringContainsString(string $needle, string $haystack, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringContainsString(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringContainsStringIgnoringCase')) {
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringContainsStringIgnoringCase
     */
    function assertStringContainsStringIgnoringCase(string $needle, string $haystack, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringContainsStringIgnoringCase(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringNotContainsString')) {
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringNotContainsString
     */
    function assertStringNotContainsString(string $needle, string $haystack, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringNotContainsString(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringNotContainsStringIgnoringCase')) {
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringNotContainsStringIgnoringCase
     */
    function assertStringNotContainsStringIgnoringCase(string $needle, string $haystack, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringNotContainsStringIgnoringCase(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringEndsWith')) {
    /**
     * Asserts that a string ends with a given suffix.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringEndsWith
     */
    function assertStringEndsWith(string $suffix, string $string, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringEndsWith(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertStringEndsNotWith')) {
    /**
     * Asserts that a string ends not with a given suffix.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertStringEndsNotWith
     */
    function assertStringEndsNotWith(string $suffix, string $string, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertStringEndsNotWith(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertXmlFileEqualsXmlFile')) {
    /**
     * Asserts that two XML files are equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertXmlFileEqualsXmlFile
     */
    function assertXmlFileEqualsXmlFile(string $expectedFile, string $actualFile, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertXmlFileEqualsXmlFile(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertXmlFileNotEqualsXmlFile')) {
    /**
     * Asserts that two XML files are not equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertXmlFileNotEqualsXmlFile
     */
    function assertXmlFileNotEqualsXmlFile(string $expectedFile, string $actualFile, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertXmlFileNotEqualsXmlFile(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertXmlStringEqualsXmlFile')) {
    /**
     * Asserts that two XML documents are equal.
     *
     * @param DOMDocument|string $actualXml
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertXmlStringEqualsXmlFile
     */
    function assertXmlStringEqualsXmlFile(string $expectedFile, $actualXml, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertXmlStringEqualsXmlFile(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertXmlStringNotEqualsXmlFile')) {
    /**
     * Asserts that two XML documents are not equal.
     *
     * @param DOMDocument|string $actualXml
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertXmlStringNotEqualsXmlFile
     */
    function assertXmlStringNotEqualsXmlFile(string $expectedFile, $actualXml, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertXmlStringNotEqualsXmlFile(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertXmlStringEqualsXmlString')) {
    /**
     * Asserts that two XML documents are equal.
     *
     * @param DOMDocument|string $expectedXml
     * @param DOMDocument|string $actualXml
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertXmlStringEqualsXmlString
     */
    function assertXmlStringEqualsXmlString($expectedXml, $actualXml, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertXmlStringEqualsXmlString(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertXmlStringNotEqualsXmlString')) {
    /**
     * Asserts that two XML documents are not equal.
     *
     * @param DOMDocument|string $expectedXml
     * @param DOMDocument|string $actualXml
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws ExpectationFailedException
     *
     * @see Assert::assertXmlStringNotEqualsXmlString
     */
    function assertXmlStringNotEqualsXmlString($expectedXml, $actualXml, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertXmlStringNotEqualsXmlString(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertEqualXMLStructure')) {
    /**
     * Asserts that a hierarchy of DOMElements matches.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws AssertionFailedError
     * @throws ExpectationFailedException
     *
     * @see Assert::assertEqualXMLStructure
     */
    function assertEqualXMLStructure(\DOMElement $expectedElement, \DOMElement $actualElement, bool $checkAttributes = \false, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertEqualXMLStructure(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertThat')) {
    /**
     * Evaluates a PHPUnit\Framework\Constraint matcher object.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertThat
     */
    function assertThat($value, \PHPUnit\Framework\Constraint\Constraint $constraint, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertThat(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertJson')) {
    /**
     * Asserts that a string is a valid JSON string.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertJson
     */
    function assertJson(string $actualJson, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertJson(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertJsonStringEqualsJsonString')) {
    /**
     * Asserts that two given JSON encoded objects or arrays are equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertJsonStringEqualsJsonString
     */
    function assertJsonStringEqualsJsonString(string $expectedJson, string $actualJson, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertJsonStringEqualsJsonString(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertJsonStringNotEqualsJsonString')) {
    /**
     * Asserts that two given JSON encoded objects or arrays are not equal.
     *
     * @param string $expectedJson
     * @param string $actualJson
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertJsonStringNotEqualsJsonString
     */
    function assertJsonStringNotEqualsJsonString($expectedJson, $actualJson, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertJsonStringNotEqualsJsonString(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertJsonStringEqualsJsonFile')) {
    /**
     * Asserts that the generated JSON encoded object and the content of the given file are equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertJsonStringEqualsJsonFile
     */
    function assertJsonStringEqualsJsonFile(string $expectedFile, string $actualJson, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertJsonStringEqualsJsonFile(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertJsonStringNotEqualsJsonFile')) {
    /**
     * Asserts that the generated JSON encoded object and the content of the given file are not equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertJsonStringNotEqualsJsonFile
     */
    function assertJsonStringNotEqualsJsonFile(string $expectedFile, string $actualJson, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertJsonStringNotEqualsJsonFile(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertJsonFileEqualsJsonFile')) {
    /**
     * Asserts that two JSON files are equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertJsonFileEqualsJsonFile
     */
    function assertJsonFileEqualsJsonFile(string $expectedFile, string $actualFile, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertJsonFileEqualsJsonFile(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\assertJsonFileNotEqualsJsonFile')) {
    /**
     * Asserts that two JSON files are not equal.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
     *
     * @see Assert::assertJsonFileNotEqualsJsonFile
     */
    function assertJsonFileNotEqualsJsonFile(string $expectedFile, string $actualFile, string $message = '') : void
    {
        \PHPUnit\Framework\Assert::assertJsonFileNotEqualsJsonFile(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\logicalAnd')) {
    function logicalAnd() : \PHPUnit\Framework\Constraint\LogicalAnd
    {
        return \PHPUnit\Framework\Assert::logicalAnd(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\logicalOr')) {
    function logicalOr() : \PHPUnit\Framework\Constraint\LogicalOr
    {
        return \PHPUnit\Framework\Assert::logicalOr(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\logicalNot')) {
    function logicalNot(\PHPUnit\Framework\Constraint\Constraint $constraint) : \PHPUnit\Framework\Constraint\LogicalNot
    {
        return \PHPUnit\Framework\Assert::logicalNot(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\logicalXor')) {
    function logicalXor() : \PHPUnit\Framework\Constraint\LogicalXor
    {
        return \PHPUnit\Framework\Assert::logicalXor(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\anything')) {
    function anything() : \PHPUnit\Framework\Constraint\IsAnything
    {
        return \PHPUnit\Framework\Assert::anything(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\isTrue')) {
    function isTrue() : \PHPUnit\Framework\Constraint\IsTrue
    {
        return \PHPUnit\Framework\Assert::isTrue(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\callback')) {
    function callback(callable $callback) : \PHPUnit\Framework\Constraint\Callback
    {
        return \PHPUnit\Framework\Assert::callback(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\isFalse')) {
    function isFalse() : \PHPUnit\Framework\Constraint\IsFalse
    {
        return \PHPUnit\Framework\Assert::isFalse(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\isJson')) {
    function isJson() : \PHPUnit\Framework\Constraint\IsJson
    {
        return \PHPUnit\Framework\Assert::isJson(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\isNull')) {
    function isNull() : \PHPUnit\Framework\Constraint\IsNull
    {
        return \PHPUnit\Framework\Assert::isNull(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\isFinite')) {
    function isFinite() : \PHPUnit\Framework\Constraint\IsFinite
    {
        return \PHPUnit\Framework\Assert::isFinite(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\isInfinite')) {
    function isInfinite() : \PHPUnit\Framework\Constraint\IsInfinite
    {
        return \PHPUnit\Framework\Assert::isInfinite(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\isNan')) {
    function isNan() : \PHPUnit\Framework\Constraint\IsNan
    {
        return \PHPUnit\Framework\Assert::isNan(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\attribute')) {
    function attribute(\PHPUnit\Framework\Constraint\Constraint $constraint, string $attributeName) : \PHPUnit\Framework\Constraint\Attribute
    {
        return \PHPUnit\Framework\Assert::attribute(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\contains')) {
    function contains($value, bool $checkForObjectIdentity = \true, bool $checkForNonObjectIdentity = \false) : \PHPUnit\Framework\Constraint\TraversableContains
    {
        return \PHPUnit\Framework\Assert::contains(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\containsEqual')) {
    function containsEqual($value) : \PHPUnit\Framework\Constraint\TraversableContainsEqual
    {
        return \PHPUnit\Framework\Assert::containsEqual(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\containsIdentical')) {
    function containsIdentical($value) : \PHPUnit\Framework\Constraint\TraversableContainsIdentical
    {
        return \PHPUnit\Framework\Assert::containsIdentical(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\containsOnly')) {
    function containsOnly(string $type) : \PHPUnit\Framework\Constraint\TraversableContainsOnly
    {
        return \PHPUnit\Framework\Assert::containsOnly(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\containsOnlyInstancesOf')) {
    function containsOnlyInstancesOf(string $className) : \PHPUnit\Framework\Constraint\TraversableContainsOnly
    {
        return \PHPUnit\Framework\Assert::containsOnlyInstancesOf(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\arrayHasKey')) {
    function arrayHasKey($key) : \PHPUnit\Framework\Constraint\ArrayHasKey
    {
        return \PHPUnit\Framework\Assert::arrayHasKey(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\equalTo')) {
    function equalTo($value, float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = \false, bool $ignoreCase = \false) : \PHPUnit\Framework\Constraint\IsEqual
    {
        return \PHPUnit\Framework\Assert::equalTo(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\attributeEqualTo')) {
    function attributeEqualTo(string $attributeName, $value, float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = \false, bool $ignoreCase = \false) : \PHPUnit\Framework\Constraint\Attribute
    {
        return \PHPUnit\Framework\Assert::attributeEqualTo(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\isEmpty')) {
    function isEmpty() : \PHPUnit\Framework\Constraint\IsEmpty
    {
        return \PHPUnit\Framework\Assert::isEmpty(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\isWritable')) {
    function isWritable() : \PHPUnit\Framework\Constraint\IsWritable
    {
        return \PHPUnit\Framework\Assert::isWritable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\isReadable')) {
    function isReadable() : \PHPUnit\Framework\Constraint\IsReadable
    {
        return \PHPUnit\Framework\Assert::isReadable(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\directoryExists')) {
    function directoryExists() : \PHPUnit\Framework\Constraint\DirectoryExists
    {
        return \PHPUnit\Framework\Assert::directoryExists(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\fileExists')) {
    function fileExists() : \PHPUnit\Framework\Constraint\FileExists
    {
        return \PHPUnit\Framework\Assert::fileExists(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\greaterThan')) {
    function greaterThan($value) : \PHPUnit\Framework\Constraint\GreaterThan
    {
        return \PHPUnit\Framework\Assert::greaterThan(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\greaterThanOrEqual')) {
    function greaterThanOrEqual($value) : \PHPUnit\Framework\Constraint\LogicalOr
    {
        return \PHPUnit\Framework\Assert::greaterThanOrEqual(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\classHasAttribute')) {
    function classHasAttribute(string $attributeName) : \PHPUnit\Framework\Constraint\ClassHasAttribute
    {
        return \PHPUnit\Framework\Assert::classHasAttribute(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\classHasStaticAttribute')) {
    function classHasStaticAttribute(string $attributeName) : \PHPUnit\Framework\Constraint\ClassHasStaticAttribute
    {
        return \PHPUnit\Framework\Assert::classHasStaticAttribute(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\objectHasAttribute')) {
    function objectHasAttribute($attributeName) : \PHPUnit\Framework\Constraint\ObjectHasAttribute
    {
        return \PHPUnit\Framework\Assert::objectHasAttribute(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\identicalTo')) {
    function identicalTo($value) : \PHPUnit\Framework\Constraint\IsIdentical
    {
        return \PHPUnit\Framework\Assert::identicalTo(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\isInstanceOf')) {
    function isInstanceOf(string $className) : \PHPUnit\Framework\Constraint\IsInstanceOf
    {
        return \PHPUnit\Framework\Assert::isInstanceOf(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\isType')) {
    function isType(string $type) : \PHPUnit\Framework\Constraint\IsType
    {
        return \PHPUnit\Framework\Assert::isType(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\lessThan')) {
    function lessThan($value) : \PHPUnit\Framework\Constraint\LessThan
    {
        return \PHPUnit\Framework\Assert::lessThan(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\lessThanOrEqual')) {
    function lessThanOrEqual($value) : \PHPUnit\Framework\Constraint\LogicalOr
    {
        return \PHPUnit\Framework\Assert::lessThanOrEqual(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\matchesRegularExpression')) {
    function matchesRegularExpression(string $pattern) : \PHPUnit\Framework\Constraint\RegularExpression
    {
        return \PHPUnit\Framework\Assert::matchesRegularExpression(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\matches')) {
    function matches(string $string) : \PHPUnit\Framework\Constraint\StringMatchesFormatDescription
    {
        return \PHPUnit\Framework\Assert::matches(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\stringStartsWith')) {
    function stringStartsWith($prefix) : \PHPUnit\Framework\Constraint\StringStartsWith
    {
        return \PHPUnit\Framework\Assert::stringStartsWith(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\stringContains')) {
    function stringContains(string $string, bool $case = \true) : \PHPUnit\Framework\Constraint\StringContains
    {
        return \PHPUnit\Framework\Assert::stringContains(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\stringEndsWith')) {
    function stringEndsWith(string $suffix) : \PHPUnit\Framework\Constraint\StringEndsWith
    {
        return \PHPUnit\Framework\Assert::stringEndsWith(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\countOf')) {
    function countOf(int $count) : \PHPUnit\Framework\Constraint\Count
    {
        return \PHPUnit\Framework\Assert::countOf(...\func_get_args());
    }
}
if (!\function_exists('PHPUnit\\Framework\\any')) {
    /**
     * Returns a matcher that matches when the method is executed
     * zero or more times.
     */
    function any() : \PHPUnit\Framework\MockObject\Rule\AnyInvokedCount
    {
        return new \PHPUnit\Framework\MockObject\Rule\AnyInvokedCount();
    }
}
if (!\function_exists('PHPUnit\\Framework\\never')) {
    /**
     * Returns a matcher that matches when the method is never executed.
     */
    function never() : \PHPUnit\Framework\MockObject\Rule\InvokedCount
    {
        return new \PHPUnit\Framework\MockObject\Rule\InvokedCount(0);
    }
}
if (!\function_exists('PHPUnit\\Framework\\atLeast')) {
    /**
     * Returns a matcher that matches when the method is executed
     * at least N times.
     */
    function atLeast(int $requiredInvocations) : \PHPUnit\Framework\MockObject\Rule\InvokedAtLeastCount
    {
        return new \PHPUnit\Framework\MockObject\Rule\InvokedAtLeastCount($requiredInvocations);
    }
}
if (!\function_exists('PHPUnit\\Framework\\atLeastOnce')) {
    /**
     * Returns a matcher that matches when the method is executed at least once.
     */
    function atLeastOnce() : \PHPUnit\Framework\MockObject\Rule\InvokedAtLeastOnce
    {
        return new \PHPUnit\Framework\MockObject\Rule\InvokedAtLeastOnce();
    }
}
if (!\function_exists('PHPUnit\\Framework\\once')) {
    /**
     * Returns a matcher that matches when the method is executed exactly once.
     */
    function once() : \PHPUnit\Framework\MockObject\Rule\InvokedCount
    {
        return new \PHPUnit\Framework\MockObject\Rule\InvokedCount(1);
    }
}
if (!\function_exists('PHPUnit\\Framework\\exactly')) {
    /**
     * Returns a matcher that matches when the method is executed
     * exactly $count times.
     */
    function exactly(int $count) : \PHPUnit\Framework\MockObject\Rule\InvokedCount
    {
        return new \PHPUnit\Framework\MockObject\Rule\InvokedCount($count);
    }
}
if (!\function_exists('PHPUnit\\Framework\\atMost')) {
    /**
     * Returns a matcher that matches when the method is executed
     * at most N times.
     */
    function atMost(int $allowedInvocations) : \PHPUnit\Framework\MockObject\Rule\InvokedAtMostCount
    {
        return new \PHPUnit\Framework\MockObject\Rule\InvokedAtMostCount($allowedInvocations);
    }
}
if (!\function_exists('PHPUnit\\Framework\\at')) {
    /**
     * Returns a matcher that matches when the method is executed
     * at the given index.
     */
    function at(int $index) : \PHPUnit\Framework\MockObject\Rule\InvokedAtIndex
    {
        return new \PHPUnit\Framework\MockObject\Rule\InvokedAtIndex($index);
    }
}
if (!\function_exists('PHPUnit\\Framework\\returnValue')) {
    function returnValue($value) : \PHPUnit\Framework\MockObject\Stub\ReturnStub
    {
        return new \PHPUnit\Framework\MockObject\Stub\ReturnStub($value);
    }
}
if (!\function_exists('PHPUnit\\Framework\\returnValueMap')) {
    function returnValueMap(array $valueMap) : \PHPUnit\Framework\MockObject\Stub\ReturnValueMap
    {
        return new \PHPUnit\Framework\MockObject\Stub\ReturnValueMap($valueMap);
    }
}
if (!\function_exists('PHPUnit\\Framework\\returnArgument')) {
    function returnArgument(int $argumentIndex) : \PHPUnit\Framework\MockObject\Stub\ReturnArgument
    {
        return new \PHPUnit\Framework\MockObject\Stub\ReturnArgument($argumentIndex);
    }
}
if (!\function_exists('PHPUnit\\Framework\\returnCallback')) {
    function returnCallback($callback) : \PHPUnit\Framework\MockObject\Stub\ReturnCallback
    {
        return new \PHPUnit\Framework\MockObject\Stub\ReturnCallback($callback);
    }
}
if (!\function_exists('PHPUnit\\Framework\\returnSelf')) {
    /**
     * Returns the current object.
     *
     * This method is useful when mocking a fluent interface.
     */
    function returnSelf() : \PHPUnit\Framework\MockObject\Stub\ReturnSelf
    {
        return new \PHPUnit\Framework\MockObject\Stub\ReturnSelf();
    }
}
if (!\function_exists('PHPUnit\\Framework\\throwException')) {
    function throwException(\Throwable $exception) : \PHPUnit\Framework\MockObject\Stub\Exception
    {
        return new \PHPUnit\Framework\MockObject\Stub\Exception($exception);
    }
}
if (!\function_exists('PHPUnit\\Framework\\onConsecutiveCalls')) {
    function onConsecutiveCalls() : \PHPUnit\Framework\MockObject\Stub\ConsecutiveCalls
    {
        $args = \func_get_args();
        return new \PHPUnit\Framework\MockObject\Stub\ConsecutiveCalls($args);
    }
}
