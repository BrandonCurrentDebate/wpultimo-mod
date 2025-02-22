<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Matcher;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Reflection\ReflectionHelper;
use ReflectionException;
/**
 * Matches a property by its type.
 *
 * It is recommended to use {@see DeepCopy\TypeFilter\TypeFilter} instead, as it applies on all occurrences
 * of given type in copied context (eg. array elements), not just on object properties.
 *
 * @final
 */
class PropertyTypeMatcher implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Matcher\Matcher
{
    /**
     * @var string
     */
    private $propertyType;
    /**
     * @param string $propertyType Property type
     */
    public function __construct($propertyType)
    {
        $this->propertyType = $propertyType;
    }
    /**
     * {@inheritdoc}
     */
    public function matches($object, $property)
    {
        try {
            $reflectionProperty = \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Reflection\ReflectionHelper::getProperty($object, $property);
        } catch (\ReflectionException $exception) {
            return \false;
        }
        $reflectionProperty->setAccessible(\true);
        // Uninitialized properties (for PHP >7.4)
        if (\method_exists($reflectionProperty, 'isInitialized') && !$reflectionProperty->isInitialized($object)) {
            // null instanceof $this->propertyType
            return \false;
        }
        return $reflectionProperty->getValue($object) instanceof $this->propertyType;
    }
}
