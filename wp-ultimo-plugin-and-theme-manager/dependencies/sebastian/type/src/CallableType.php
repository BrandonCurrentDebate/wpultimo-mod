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

final class CallableType extends \SebastianBergmann\Type\Type
{
    /**
     * @var bool
     */
    private $allowsNull;
    public function __construct(bool $nullable)
    {
        $this->allowsNull = $nullable;
    }
    /**
     * @throws RuntimeException
     */
    public function isAssignable(\SebastianBergmann\Type\Type $other) : bool
    {
        if ($this->allowsNull && $other instanceof \SebastianBergmann\Type\NullType) {
            return \true;
        }
        if ($other instanceof self) {
            return \true;
        }
        if ($other instanceof \SebastianBergmann\Type\ObjectType) {
            if ($this->isClosure($other)) {
                return \true;
            }
            if ($this->hasInvokeMethod($other)) {
                return \true;
            }
        }
        if ($other instanceof \SebastianBergmann\Type\SimpleType) {
            if ($this->isFunction($other)) {
                return \true;
            }
            if ($this->isClassCallback($other)) {
                return \true;
            }
            if ($this->isObjectCallback($other)) {
                return \true;
            }
        }
        return \false;
    }
    public function getReturnTypeDeclaration() : string
    {
        return ': ' . ($this->allowsNull ? '?' : '') . 'callable';
    }
    public function allowsNull() : bool
    {
        return $this->allowsNull;
    }
    private function isClosure(\SebastianBergmann\Type\ObjectType $type) : bool
    {
        return !$type->className()->isNamespaced() && $type->className()->getSimpleName() === \Closure::class;
    }
    /**
     * @throws RuntimeException
     */
    private function hasInvokeMethod(\SebastianBergmann\Type\ObjectType $type) : bool
    {
        try {
            $class = new \ReflectionClass($type->className()->getQualifiedName());
            // @codeCoverageIgnoreStart
        } catch (\ReflectionException $e) {
            throw new \SebastianBergmann\Type\RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
            // @codeCoverageIgnoreEnd
        }
        if ($class->hasMethod('__invoke')) {
            return \true;
        }
        return \false;
    }
    private function isFunction(\SebastianBergmann\Type\SimpleType $type) : bool
    {
        if (!\is_string($type->value())) {
            return \false;
        }
        return \function_exists($type->value());
    }
    private function isObjectCallback(\SebastianBergmann\Type\SimpleType $type) : bool
    {
        if (!\is_array($type->value())) {
            return \false;
        }
        if (\count($type->value()) !== 2) {
            return \false;
        }
        if (!\is_object($type->value()[0]) || !\is_string($type->value()[1])) {
            return \false;
        }
        [$object, $methodName] = $type->value();
        $reflector = new \ReflectionObject($object);
        return $reflector->hasMethod($methodName);
    }
    private function isClassCallback(\SebastianBergmann\Type\SimpleType $type) : bool
    {
        if (!\is_string($type->value()) && !\is_array($type->value())) {
            return \false;
        }
        if (\is_string($type->value())) {
            if (\strpos($type->value(), '::') === \false) {
                return \false;
            }
            [$className, $methodName] = \explode('::', $type->value());
        }
        if (\is_array($type->value())) {
            if (\count($type->value()) !== 2) {
                return \false;
            }
            if (!\is_string($type->value()[0]) || !\is_string($type->value()[1])) {
                return \false;
            }
            [$className, $methodName] = $type->value();
        }
        \assert(isset($className) && \is_string($className));
        \assert(isset($methodName) && \is_string($methodName));
        try {
            $class = new \ReflectionClass($className);
            if ($class->hasMethod($methodName)) {
                $method = $class->getMethod($methodName);
                return $method->isPublic() && $method->isStatic();
            }
            // @codeCoverageIgnoreStart
        } catch (\ReflectionException $e) {
            throw new \SebastianBergmann\Type\RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
            // @codeCoverageIgnoreEnd
        }
        return \false;
    }
}
