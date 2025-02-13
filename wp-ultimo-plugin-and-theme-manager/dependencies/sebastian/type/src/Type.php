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

abstract class Type
{
    public static function fromValue($value, bool $allowsNull) : self
    {
        $typeName = \gettype($value);
        if ($typeName === 'object') {
            return new \SebastianBergmann\Type\ObjectType(\SebastianBergmann\Type\TypeName::fromQualifiedName(\get_class($value)), $allowsNull);
        }
        $type = self::fromName($typeName, $allowsNull);
        if ($type instanceof \SebastianBergmann\Type\SimpleType) {
            $type = new \SebastianBergmann\Type\SimpleType($typeName, $allowsNull, $value);
        }
        return $type;
    }
    public static function fromName(string $typeName, bool $allowsNull) : self
    {
        switch (\strtolower($typeName)) {
            case 'callable':
                return new \SebastianBergmann\Type\CallableType($allowsNull);
            case 'iterable':
                return new \SebastianBergmann\Type\IterableType($allowsNull);
            case 'null':
                return new \SebastianBergmann\Type\NullType();
            case 'object':
                return new \SebastianBergmann\Type\GenericObjectType($allowsNull);
            case 'unknown type':
                return new \SebastianBergmann\Type\UnknownType();
            case 'void':
                return new \SebastianBergmann\Type\VoidType();
            case 'array':
            case 'bool':
            case 'boolean':
            case 'double':
            case 'float':
            case 'int':
            case 'integer':
            case 'real':
            case 'resource':
            case 'resource (closed)':
            case 'string':
                return new \SebastianBergmann\Type\SimpleType($typeName, $allowsNull);
            default:
                return new \SebastianBergmann\Type\ObjectType(\SebastianBergmann\Type\TypeName::fromQualifiedName($typeName), $allowsNull);
        }
    }
    public abstract function isAssignable(\SebastianBergmann\Type\Type $other) : bool;
    public abstract function getReturnTypeDeclaration() : string;
    public abstract function allowsNull() : bool;
}
