<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Promise;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\ObjectProphecy;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\MethodProphecy;
/**
 * Return promise.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class ReturnPromise implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Promise\PromiseInterface
{
    private $returnValues = array();
    /**
     * Initializes promise.
     *
     * @param array $returnValues Array of values
     */
    public function __construct(array $returnValues)
    {
        $this->returnValues = $returnValues;
    }
    /**
     * Returns saved values one by one until last one, then continuously returns last value.
     *
     * @param array          $args
     * @param ObjectProphecy $object
     * @param MethodProphecy $method
     *
     * @return mixed
     */
    public function execute(array $args, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\ObjectProphecy $object, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\MethodProphecy $method)
    {
        $value = \array_shift($this->returnValues);
        if (!\count($this->returnValues)) {
            $this->returnValues[] = $value;
        }
        return $value;
    }
}
