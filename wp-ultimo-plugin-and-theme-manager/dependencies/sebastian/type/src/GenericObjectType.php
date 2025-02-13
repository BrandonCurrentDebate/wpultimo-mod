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

final class GenericObjectType extends \SebastianBergmann\Type\Type
{
    /**
     * @var bool
     */
    private $allowsNull;
    public function __construct(bool $nullable)
    {
        $this->allowsNull = $nullable;
    }
    public function isAssignable(\SebastianBergmann\Type\Type $other) : bool
    {
        if ($this->allowsNull && $other instanceof \SebastianBergmann\Type\NullType) {
            return \true;
        }
        if (!$other instanceof \SebastianBergmann\Type\ObjectType) {
            return \false;
        }
        return \true;
    }
    public function getReturnTypeDeclaration() : string
    {
        return ': ' . ($this->allowsNull ? '?' : '') . 'object';
    }
    public function allowsNull() : bool
    {
        return $this->allowsNull;
    }
}
