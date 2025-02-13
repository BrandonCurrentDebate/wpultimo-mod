<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Prediction;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\MethodProphecy;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Prophecy\MethodProphecyException;
class UnexpectedCallsException extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Prophecy\MethodProphecyException implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Prediction\PredictionException
{
    private $calls = array();
    public function __construct($message, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\MethodProphecy $methodProphecy, array $calls)
    {
        parent::__construct($message, $methodProphecy);
        $this->calls = $calls;
    }
    public function getCalls()
    {
        return $this->calls;
    }
}
