<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Matcher;

/**
 * @final
 */
class PropertyMatcher implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Matcher\Matcher
{
    /**
     * @var string
     */
    private $class;
    /**
     * @var string
     */
    private $property;
    /**
     * @param string $class    Class name
     * @param string $property Property name
     */
    public function __construct($class, $property)
    {
        $this->class = $class;
        $this->property = $property;
    }
    /**
     * Matches a specific property of a specific class.
     *
     * {@inheritdoc}
     */
    public function matches($object, $property)
    {
        return $object instanceof $this->class && $property == $this->property;
    }
}
