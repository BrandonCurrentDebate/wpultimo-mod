<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Matcher;

/**
 * @final
 */
class PropertyNameMatcher implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Matcher\Matcher
{
    /**
     * @var string
     */
    private $property;
    /**
     * @param string $property Property name
     */
    public function __construct($property)
    {
        $this->property = $property;
    }
    /**
     * Matches a property by its name.
     *
     * {@inheritdoc}
     */
    public function matches($object, $property)
    {
        return $property == $this->property;
    }
}
