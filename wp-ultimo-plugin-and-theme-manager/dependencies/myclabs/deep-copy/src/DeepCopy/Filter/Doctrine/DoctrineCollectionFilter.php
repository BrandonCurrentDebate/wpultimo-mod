<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Filter\Doctrine;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Filter\Filter;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Reflection\ReflectionHelper;
/**
 * @final
 */
class DoctrineCollectionFilter implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Filter\Filter
{
    /**
     * Copies the object property doctrine collection.
     *
     * {@inheritdoc}
     */
    public function apply($object, $property, $objectCopier)
    {
        $reflectionProperty = \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\Reflection\ReflectionHelper::getProperty($object, $property);
        $reflectionProperty->setAccessible(\true);
        $oldCollection = $reflectionProperty->getValue($object);
        $newCollection = $oldCollection->map(function ($item) use($objectCopier) {
            return $objectCopier($item);
        });
        $reflectionProperty->setValue($object, $newCollection);
    }
}
