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
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Argument\ArgumentsWildcard;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Argument\Token\AnyValuesToken;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Util\StringUtil;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Prediction\NoCallsException;
/**
 * Call prediction.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class CallPrediction implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prediction\PredictionInterface
{
    private $util;
    /**
     * Initializes prediction.
     *
     * @param StringUtil $util
     */
    public function __construct(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Util\StringUtil $util = null)
    {
        $this->util = $util ?: new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Util\StringUtil();
    }
    /**
     * Tests that there was at least one call.
     *
     * @param Call[]         $calls
     * @param ObjectProphecy $object
     * @param MethodProphecy $method
     *
     * @throws \Prophecy\Exception\Prediction\NoCallsException
     */
    public function check(array $calls, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\ObjectProphecy $object, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\MethodProphecy $method)
    {
        if (\count($calls)) {
            return;
        }
        $methodCalls = $object->findProphecyMethodCalls($method->getMethodName(), new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Argument\ArgumentsWildcard(array(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Argument\Token\AnyValuesToken())));
        if (\count($methodCalls)) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Prediction\NoCallsException(\sprintf("No calls have been made that match:\n" . "  %s->%s(%s)\n" . "but expected at least one.\n" . "Recorded `%s(...)` calls:\n%s", \get_class($object->reveal()), $method->getMethodName(), $method->getArgumentsWildcard(), $method->getMethodName(), $this->util->stringifyCalls($methodCalls)), $method);
        }
        throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Prediction\NoCallsException(\sprintf("No calls have been made that match:\n" . "  %s->%s(%s)\n" . "but expected at least one.", \get_class($object->reveal()), $method->getMethodName(), $method->getArgumentsWildcard()), $method);
    }
}
