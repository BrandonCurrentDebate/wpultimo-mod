<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Comparator;

use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;
/**
 * Closure comparator.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
final class ClosureComparator extends \SebastianBergmann\Comparator\Comparator
{
    public function accepts($expected, $actual)
    {
        return \is_object($expected) && $expected instanceof \Closure && \is_object($actual) && $actual instanceof \Closure;
    }
    public function assertEquals($expected, $actual, $delta = 0.0, $canonicalize = \false, $ignoreCase = \false, array &$processed = array())
    {
        if ($expected !== $actual) {
            throw new \SebastianBergmann\Comparator\ComparisonFailure(
                $expected,
                $actual,
                // we don't need a diff
                '',
                '',
                \false,
                'all closures are different if not identical'
            );
        }
    }
}
