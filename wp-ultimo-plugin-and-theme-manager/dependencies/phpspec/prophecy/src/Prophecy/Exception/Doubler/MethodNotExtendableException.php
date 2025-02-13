<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Doubler;

class MethodNotExtendableException extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Doubler\DoubleException
{
    private $methodName;
    private $className;
    /**
     * @param string $message
     * @param string $className
     * @param string $methodName
     */
    public function __construct($message, $className, $methodName)
    {
        parent::__construct($message);
        $this->methodName = $methodName;
        $this->className = $className;
    }
    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }
    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
}
