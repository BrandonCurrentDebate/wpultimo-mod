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

use SebastianBergmann\Exporter\Exporter;
/**
 * Abstract base class for comparators which compare values for equality.
 */
abstract class Comparator
{
    /**
     * @var Factory
     */
    protected $factory;
    /**
     * @var Exporter
     */
    protected $exporter;
    public function __construct()
    {
        $this->exporter = new \SebastianBergmann\Exporter\Exporter();
    }
    public function setFactory(\SebastianBergmann\Comparator\Factory $factory)
    {
        $this->factory = $factory;
    }
    /**
     * Returns whether the comparator can compare two values.
     *
     * @param mixed $expected The first value to compare
     * @param mixed $actual   The second value to compare
     *
     * @return bool
     */
    public abstract function accepts($expected, $actual);
    /**
     * Asserts that two values are equal.
     *
     * @param mixed $expected     First value to compare
     * @param mixed $actual       Second value to compare
     * @param float $delta        Allowed numerical distance between two values to consider them equal
     * @param bool  $canonicalize Arrays are sorted before comparison when set to true
     * @param bool  $ignoreCase   Case is ignored when set to true
     *
     * @throws ComparisonFailure
     */
    public abstract function assertEquals($expected, $actual, $delta = 0.0, $canonicalize = \false, $ignoreCase = \false);
}
