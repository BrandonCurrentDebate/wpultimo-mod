<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\TypeHintReference;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\InvalidArgumentException;
/**
 * Method node.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class MethodNode
{
    private $name;
    private $code;
    private $visibility = 'public';
    private $static = \false;
    private $returnsReference = \false;
    /** @var ReturnTypeNode */
    private $returnTypeNode;
    /**
     * @var ArgumentNode[]
     */
    private $arguments = array();
    /**
     * @param string $name
     * @param string $code
     */
    public function __construct($name, $code = null, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\TypeHintReference $typeHintReference = null)
    {
        $this->name = $name;
        $this->code = $code;
        $this->returnTypeNode = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ReturnTypeNode();
    }
    public function getVisibility()
    {
        return $this->visibility;
    }
    /**
     * @param string $visibility
     */
    public function setVisibility($visibility)
    {
        $visibility = \strtolower($visibility);
        if (!\in_array($visibility, array('public', 'private', 'protected'))) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\InvalidArgumentException(\sprintf('`%s` method visibility is not supported.', $visibility));
        }
        $this->visibility = $visibility;
    }
    public function isStatic()
    {
        return $this->static;
    }
    public function setStatic($static = \true)
    {
        $this->static = (bool) $static;
    }
    public function returnsReference()
    {
        return $this->returnsReference;
    }
    public function setReturnsReference()
    {
        $this->returnsReference = \true;
    }
    public function getName()
    {
        return $this->name;
    }
    public function addArgument(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ArgumentNode $argument)
    {
        $this->arguments[] = $argument;
    }
    /**
     * @return ArgumentNode[]
     */
    public function getArguments()
    {
        return $this->arguments;
    }
    /**
     * @deprecated use getReturnTypeNode instead
     * @return bool
     */
    public function hasReturnType()
    {
        return (bool) $this->returnTypeNode->getNonNullTypes();
    }
    public function setReturnTypeNode(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ReturnTypeNode $returnTypeNode) : void
    {
        $this->returnTypeNode = $returnTypeNode;
    }
    /**
     * @deprecated use setReturnTypeNode instead
     * @param string $type
     */
    public function setReturnType($type = null)
    {
        $this->returnTypeNode = $type === '' || $type === null ? new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ReturnTypeNode() : new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ReturnTypeNode($type);
    }
    /**
     * @deprecated use setReturnTypeNode instead
     * @param bool $bool
     */
    public function setNullableReturnType($bool = \true)
    {
        if ($bool) {
            $this->returnTypeNode = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ReturnTypeNode('null', ...$this->returnTypeNode->getTypes());
        } else {
            $this->returnTypeNode = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ReturnTypeNode(...$this->returnTypeNode->getNonNullTypes());
        }
    }
    /**
     * @deprecated use getReturnTypeNode instead
     * @return string|null
     */
    public function getReturnType()
    {
        if ($types = $this->returnTypeNode->getNonNullTypes()) {
            return $types[0];
        }
        return null;
    }
    public function getReturnTypeNode() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ReturnTypeNode
    {
        return $this->returnTypeNode;
    }
    /**
     * @deprecated use getReturnTypeNode instead
     * @return bool
     */
    public function hasNullableReturnType()
    {
        return $this->returnTypeNode->canUseNullShorthand();
    }
    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
    public function getCode()
    {
        if ($this->returnsReference) {
            return "throw new \\Prophecy\\Exception\\Doubler\\ReturnByReferenceException('Returning by reference not supported', get_class(\$this), '{$this->name}');";
        }
        return (string) $this->code;
    }
    public function useParentCode()
    {
        $this->code = \sprintf('return parent::%s(%s);', $this->getName(), \implode(', ', \array_map(array($this, 'generateArgument'), $this->arguments)));
    }
    private function generateArgument(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ArgumentNode $arg)
    {
        $argument = '$' . $arg->getName();
        if ($arg->isVariadic()) {
            $argument = '...' . $argument;
        }
        return $argument;
    }
}
