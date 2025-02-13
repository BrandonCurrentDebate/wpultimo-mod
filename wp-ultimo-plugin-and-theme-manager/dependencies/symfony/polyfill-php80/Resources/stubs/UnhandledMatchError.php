<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies;

if (\PHP_VERSION_ID < 80000) {
    class UnhandledMatchError extends \Error
    {
    }
}
