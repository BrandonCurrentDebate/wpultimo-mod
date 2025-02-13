<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\TypeFilter;

/**
 * @final
 */
class ShallowCopyFilter implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\TypeFilter\TypeFilter
{
    /**
     * {@inheritdoc}
     */
    public function apply($element)
    {
        return clone $element;
    }
}
