<?php

namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Doctrine\Instantiator;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Doctrine\Instantiator\Exception\ExceptionInterface;
/**
 * Instantiator provides utility methods to build objects without invoking their constructors
 */
interface InstantiatorInterface
{
    /**
     * @param string $className
     *
     * @return object
     *
     * @throws ExceptionInterface
     *
     * @template T of object
     * @phpstan-param class-string<T> $className
     */
    public function instantiate($className);
}
