<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\TypeFilter;

/**
 * @final
 */
class ReplaceFilter implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\TypeFilter\TypeFilter
{
    /**
     * @var callable
     */
    protected $callback;
    /**
     * @param callable $callable Will be called to get the new value for each element to replace
     */
    public function __construct(callable $callable)
    {
        $this->callback = $callable;
    }
    /**
     * {@inheritdoc}
     */
    public function apply($element)
    {
        return \call_user_func($this->callback, $element);
    }
}
