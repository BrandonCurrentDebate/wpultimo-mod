<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\TypeFilter\Spl;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\DeepCopy;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\TypeFilter\TypeFilter;
/**
 * In PHP 7.4 the storage of an ArrayObject isn't returned as
 * ReflectionProperty. So we deep copy its array copy.
 */
final class ArrayObjectFilter implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\TypeFilter\TypeFilter
{
    /**
     * @var DeepCopy
     */
    private $copier;
    public function __construct(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\DeepCopy $copier)
    {
        $this->copier = $copier;
    }
    /**
     * {@inheritdoc}
     */
    public function apply($arrayObject)
    {
        $clone = clone $arrayObject;
        foreach ($arrayObject->getArrayCopy() as $k => $v) {
            $clone->offsetSet($k, $this->copier->copy($v));
        }
        return $clone;
    }
}
