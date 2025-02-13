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
 * Factory for comparators which compare values for equality.
 */
class Factory
{
    /**
     * @var Factory
     */
    private static $instance;
    /**
     * @var Comparator[]
     */
    private $customComparators = [];
    /**
     * @var Comparator[]
     */
    private $defaultComparators = [];
    /**
     * @return Factory
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * Constructs a new factory.
     */
    public function __construct()
    {
        $this->registerDefaultComparators();
    }
    /**
     * Returns the correct comparator for comparing two values.
     *
     * @param mixed $expected The first value to compare
     * @param mixed $actual   The second value to compare
     *
     * @return Comparator
     */
    public function getComparatorFor($expected, $actual)
    {
        foreach ($this->customComparators as $comparator) {
            if ($comparator->accepts($expected, $actual)) {
                return $comparator;
            }
        }
        foreach ($this->defaultComparators as $comparator) {
            if ($comparator->accepts($expected, $actual)) {
                return $comparator;
            }
        }
    }
    /**
     * Registers a new comparator.
     *
     * This comparator will be returned by getComparatorFor() if its accept() method
     * returns TRUE for the compared values. It has higher priority than the
     * existing comparators, meaning that its accept() method will be invoked
     * before those of the other comparators.
     *
     * @param Comparator $comparator The comparator to be registered
     */
    public function register(\SebastianBergmann\Comparator\Comparator $comparator)
    {
        \array_unshift($this->customComparators, $comparator);
        $comparator->setFactory($this);
    }
    /**
     * Unregisters a comparator.
     *
     * This comparator will no longer be considered by getComparatorFor().
     *
     * @param Comparator $comparator The comparator to be unregistered
     */
    public function unregister(\SebastianBergmann\Comparator\Comparator $comparator)
    {
        foreach ($this->customComparators as $key => $_comparator) {
            if ($comparator === $_comparator) {
                unset($this->customComparators[$key]);
            }
        }
    }
    /**
     * Unregisters all non-default comparators.
     */
    public function reset()
    {
        $this->customComparators = [];
    }
    private function registerDefaultComparators()
    {
        $this->registerDefaultComparator(new \SebastianBergmann\Comparator\MockObjectComparator());
        $this->registerDefaultComparator(new \SebastianBergmann\Comparator\DateTimeComparator());
        $this->registerDefaultComparator(new \SebastianBergmann\Comparator\DOMNodeComparator());
        $this->registerDefaultComparator(new \SebastianBergmann\Comparator\SplObjectStorageComparator());
        $this->registerDefaultComparator(new \SebastianBergmann\Comparator\ExceptionComparator());
        $this->registerDefaultComparator(new \SebastianBergmann\Comparator\ObjectComparator());
        $this->registerDefaultComparator(new \SebastianBergmann\Comparator\ResourceComparator());
        $this->registerDefaultComparator(new \SebastianBergmann\Comparator\ArrayComparator());
        $this->registerDefaultComparator(new \SebastianBergmann\Comparator\DoubleComparator());
        $this->registerDefaultComparator(new \SebastianBergmann\Comparator\NumericComparator());
        $this->registerDefaultComparator(new \SebastianBergmann\Comparator\ScalarComparator());
        $this->registerDefaultComparator(new \SebastianBergmann\Comparator\TypeComparator());
    }
    private function registerDefaultComparator(\SebastianBergmann\Comparator\Comparator $comparator)
    {
        $this->defaultComparators[] = $comparator;
        $comparator->setFactory($this);
    }
}
