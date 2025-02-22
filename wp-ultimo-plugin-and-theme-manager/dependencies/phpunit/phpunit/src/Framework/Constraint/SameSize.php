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

final class SameSize extends \PHPUnit\Framework\Constraint\Count
{
    public function __construct(iterable $expected)
    {
        parent::__construct($this->getCountOf($expected));
    }
}
