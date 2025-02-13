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
namespace PHPUnit\Framework\MockObject;

use function array_map;
use function implode;
use function is_object;
use function ltrim;
use function sprintf;
use function strpos;
use function strtolower;
use function substr;
use PHPUnit\Framework\SelfDescribing;
use PHPUnit\Util\Type;
use SebastianBergmann\Exporter\Exporter;
use stdClass;
/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class Invocation implements \PHPUnit\Framework\SelfDescribing
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $methodName;
    /**
     * @var array
     */
    private $parameters;
    /**
     * @var string
     */
    private $returnType;
    /**
     * @var bool
     */
    private $isReturnTypeNullable = \false;
    /**
     * @var bool
     */
    private $proxiedCall;
    /**
     * @var object
     */
    private $object;
    public function __construct(string $className, string $methodName, array $parameters, string $returnType, object $object, bool $cloneObjects = \false, bool $proxiedCall = \false)
    {
        $this->className = $className;
        $this->methodName = $methodName;
        $this->parameters = $parameters;
        $this->object = $object;
        $this->proxiedCall = $proxiedCall;
        $returnType = \ltrim($returnType, ': ');
        if (\strtolower($methodName) === '__tostring') {
            $returnType = 'string';
        }
        if (\strpos($returnType, '?') === 0) {
            $returnType = \substr($returnType, 1);
            $this->isReturnTypeNullable = \true;
        }
        $this->returnType = $returnType;
        if (!$cloneObjects) {
            return;
        }
        foreach ($this->parameters as $key => $value) {
            if (\is_object($value)) {
                $this->parameters[$key] = $this->cloneObject($value);
            }
        }
    }
    public function getClassName() : string
    {
        return $this->className;
    }
    public function getMethodName() : string
    {
        return $this->methodName;
    }
    public function getParameters() : array
    {
        return $this->parameters;
    }
    /**
     * @throws RuntimeException
     *
     * @return mixed Mocked return value
     */
    public function generateReturnValue()
    {
        if ($this->isReturnTypeNullable || $this->proxiedCall) {
            return;
        }
        switch (\strtolower($this->returnType)) {
            case '':
            case 'void':
                return;
            case 'string':
                return '';
            case 'float':
                return 0.0;
            case 'int':
                return 0;
            case 'bool':
                return \false;
            case 'array':
                return [];
            case 'object':
                return new \stdClass();
            case 'callable':
            case 'closure':
                return static function () : void {
                };
            case 'traversable':
            case 'generator':
            case 'iterable':
                $generator = static function () {
                    yield from [];
                };
                return $generator();
            default:
                $generator = new \PHPUnit\Framework\MockObject\Generator();
                return $generator->getMock($this->returnType, [], [], '', \false);
        }
    }
    public function toString() : string
    {
        $exporter = new \SebastianBergmann\Exporter\Exporter();
        return \sprintf('%s::%s(%s)%s', $this->className, $this->methodName, \implode(', ', \array_map([$exporter, 'shortenedExport'], $this->parameters)), $this->returnType ? \sprintf(': %s', $this->returnType) : '');
    }
    public function getObject() : object
    {
        return $this->object;
    }
    private function cloneObject(object $original) : object
    {
        if (\PHPUnit\Util\Type::isCloneable($original)) {
            return clone $original;
        }
        return $original;
    }
}
