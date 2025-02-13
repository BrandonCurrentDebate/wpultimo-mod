<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy;

use function function_exists;
if (\false === \function_exists('WP_Ultimo_Plugin_And_Theme_Manager\\Dependencies\\DeepCopy\\deep_copy')) {
    /**
     * Deep copies the given value.
     *
     * @param mixed $value
     * @param bool  $useCloneMethod
     *
     * @return mixed
     */
    function deep_copy($value, $useCloneMethod = \false)
    {
        return (new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\DeepCopy\DeepCopy($useCloneMethod))->copy($value);
    }
}
