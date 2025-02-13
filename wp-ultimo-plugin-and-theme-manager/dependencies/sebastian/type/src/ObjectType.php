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

final class ObjectType extends \SebastianBergmann\Type\Type
{
    /**
     * @var TypeName
     */
    private $className;
    /**
     * @var bool
     */
    private $allowsNull;
    public function __construct(\SebastianBergmann\Type\TypeName $className, bool $allowsNull)
    {
        $this->className = $className;
        $this->allowsNull = $allowsNull;
    }
    public function isAssignable(\SebastianBergmann\Type\Type $other) : bool
    {
        if ($this->allowsNull && $other instanceof \SebastianBergmann\Type\NullType) {
            return \true;
        }
        if ($other instanceof self) {
            if (0 === \strcasecmp($this->className->getQualifiedName(), $other->className->getQualifiedName())) {
                return \true;
            }
            if (\is_subclass_of($other->className->getQualifiedName(), $this->className->getQualifiedName(), \true)) {
                return \true;
            }
        }
        return \false;
    }
    public function getReturnTypeDeclaration() : string
    {
        return ': ' . ($this->allowsNull ? '?' : '') . $this->className->getQualifiedName();
    }
    public function allowsNull() : bool
    {
        return $this->allowsNull;
    }
    public function className() : \SebastianBergmann\Type\TypeName
    {
        return $this->className;
    }
}
