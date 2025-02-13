<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Argument\Token;

/**
 * Any single value token.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class AnyValueToken implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Argument\Token\TokenInterface
{
    /**
     * Always scores 3 for any argument.
     *
     * @param $argument
     *
     * @return int
     */
    public function scoreArgument($argument)
    {
        return 3;
    }
    /**
     * Returns false.
     *
     * @return bool
     */
    public function isLast()
    {
        return \false;
    }
    /**
     * Returns string representation for token.
     *
     * @return string
     */
    public function __toString()
    {
        return '*';
    }
}
