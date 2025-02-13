<?php

declare (strict_types=1);
/*
 * This file is part of sebastian/type.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\Type;

final class NullType extends \SebastianBergmann\Type\Type
{
    public function isAssignable(\SebastianBergmann\Type\Type $other) : bool
    {
        return !$other instanceof \SebastianBergmann\Type\VoidType;
    }
    public function getReturnTypeDeclaration() : string
    {
        return '';
    }
    public function allowsNull() : bool
    {
        return \true;
    }
}
