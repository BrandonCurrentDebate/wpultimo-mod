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
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Util\StringUtil;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Prediction\UnexpectedCallsException;
/**
 * No calls prediction.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class NoCallsPrediction implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prediction\PredictionInterface
{
    private $util;
    /**
     * Initializes prediction.
     *
     * @param null|StringUtil $util
     */
    public function __construct(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Util\StringUtil $util = null)
    {
        $this->util = $util ?: new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Util\StringUtil();
    }
    /**
     * Tests that there were no calls made.
     *
     * @param Call[]         $calls
     * @param ObjectProphecy $object
     * @param MethodProphecy $method
     *
     * @throws \Prophecy\Exception\Prediction\UnexpectedCallsException
     */
    public function check(array $calls, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\ObjectProphecy $object, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\MethodProphecy $method)
    {
        if (!\count($calls)) {
            return;
        }
        $verb = \count($calls) === 1 ? 'was' : 'were';
        throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Prediction\UnexpectedCallsException(\sprintf("No calls expected that match:\n" . "  %s->%s(%s)\n" . "but %d %s made:\n%s", \get_class($object->reveal()), $method->getMethodName(), $method->getArgumentsWildcard(), \count($calls), $verb, $this->util->stringifyCalls($calls)), $method, $calls);
    }
}
