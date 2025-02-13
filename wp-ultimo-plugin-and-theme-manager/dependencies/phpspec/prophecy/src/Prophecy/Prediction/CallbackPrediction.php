<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prediction;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Call\Call;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\ObjectProphecy;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\MethodProphecy;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\InvalidArgumentException;
use Closure;
use ReflectionFunction;
/**
 * Callback prediction.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class CallbackPrediction implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prediction\PredictionInterface
{
    private $callback;
    /**
     * Initializes callback prediction.
     *
     * @param callable $callback Custom callback
     *
     * @throws \Prophecy\Exception\InvalidArgumentException
     */
    public function __construct($callback)
    {
        if (!\is_callable($callback)) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\InvalidArgumentException(\sprintf('Callable expected as an argument to CallbackPrediction, but got %s.', \gettype($callback)));
        }
        $this->callback = $callback;
    }
    /**
     * Executes preset callback.
     *
     * @param Call[]         $calls
     * @param ObjectProphecy $object
     * @param MethodProphecy $method
     */
    public function check(array $calls, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\ObjectProphecy $object, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\MethodProphecy $method)
    {
        $callback = $this->callback;
        if ($callback instanceof \Closure && \method_exists('Closure', 'bind') && (new \ReflectionFunction($callback))->getClosureThis() !== null) {
            $callback = \Closure::bind($callback, $object);
        }
        \call_user_func($callback, $calls, $object, $method);
    }
}
