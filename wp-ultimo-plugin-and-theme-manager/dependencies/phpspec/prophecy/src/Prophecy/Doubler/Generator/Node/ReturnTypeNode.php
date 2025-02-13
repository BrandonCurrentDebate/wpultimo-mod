<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Doubler\DoubleException;
final class ReturnTypeNode extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\TypeNodeAbstract
{
    protected function getRealType(string $type) : string
    {
        switch ($type) {
            case 'void':
            case 'never':
                return $type;
            default:
                return parent::getRealType($type);
        }
    }
    protected function guardIsValidType()
    {
        if (isset($this->types['void']) && \count($this->types) !== 1) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Doubler\DoubleException('void cannot be part of a union');
        }
        if (isset($this->types['never']) && \count($this->types) !== 1) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Doubler\DoubleException('never cannot be part of a union');
        }
        parent::guardIsValidType();
    }
    /**
     * @deprecated use hasReturnStatement
     */
    public function isVoid()
    {
        return $this->types == ['void' => 'void'];
    }
    public function hasReturnStatement() : bool
    {
        return $this->types !== ['void' => 'void'] && $this->types !== ['never' => 'never'];
    }
}
