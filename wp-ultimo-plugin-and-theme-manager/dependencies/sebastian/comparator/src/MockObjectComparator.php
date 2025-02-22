<?php

/*
 * This file is part of sebastian/comparator.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\Comparator;

/**
 * Compares PHPUnit_Framework_MockObject_MockObject instances for equality.
 */
class MockObjectComparator extends \SebastianBergmann\Comparator\ObjectComparator
{
    /**
     * Returns whether the comparator can compare two values.
     *
     * @param mixed $expected The first value to compare
     * @param mixed $actual   The second value to compare
     *
     * @return bool
     */
    public function accepts($expected, $actual)
    {
        return ($expected instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHPUnit_Framework_MockObject_MockObject || $expected instanceof \PHPUnit\Framework\MockObject\MockObject) && ($actual instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PHPUnit_Framework_MockObject_MockObject || $actual instanceof \PHPUnit\Framework\MockObject\MockObject);
    }
    /**
     * Converts an object to an array containing all of its private, protected
     * and public properties.
     *
     * @param object $object
     *
     * @return array
     */
    protected function toArray($object)
    {
        $array = parent::toArray($object);
        unset($array['__phpunit_invocationMocker']);
        return $array;
    }
}
