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
namespace PHPUnit\Framework\Constraint;

use PHPUnit\Framework\ExpectationFailedException;
/**
 * Constraint that accepts any input value.
 */
final class IsAnything extends \PHPUnit\Framework\Constraint\Constraint
{
    /**
     * Evaluates the constraint for parameter $other.
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @throws ExpectationFailedException
     */
    public function evaluate($other, string $description = '', bool $returnResult = \false)
    {
        return $returnResult ? \true : null;
    }
    /**
     * Returns a string representation of the constraint.
     */
    public function toString() : string
    {
        return 'is anything';
    }
    /**
     * Counts the number of constraint elements.
     */
    public function count() : int
    {
        return 0;
    }
}
