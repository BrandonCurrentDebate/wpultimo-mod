<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Filter\Doctrine;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Filter\Filter;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Reflection\ReflectionHelper;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Doctrine\Common\Collections\ArrayCollection;
/**
 * @final
 */
class DoctrineEmptyCollectionFilter implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Filter\Filter
{
    /**
     * Sets the object property to an empty doctrine collection.
     *
     * @param object   $object
     * @param string   $property
     * @param callable $objectCopier
     */
    public function apply($object, $property, $objectCopier)
    {
        $reflectionProperty = \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Reflection\ReflectionHelper::getProperty($object, $property);
        $reflectionProperty->setAccessible(\true);
        $reflectionProperty->setValue($object, new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Doctrine\Common\Collections\ArrayCollection());
    }
}
