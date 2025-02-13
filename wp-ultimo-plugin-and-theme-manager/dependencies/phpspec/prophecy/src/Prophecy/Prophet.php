<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\CachedDoubler;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Doubler;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\LazyDouble;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\ObjectProphecy;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\RevealerInterface;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\Revealer;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Call\CallCenter;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Util\StringUtil;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Prediction\PredictionException;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Prediction\AggregateException;
/**
 * Prophet creates prophecies.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class Prophet
{
    private $doubler;
    private $revealer;
    private $util;
    /**
     * @var ObjectProphecy[]
     */
    private $prophecies = array();
    /**
     * Initializes Prophet.
     *
     * @param null|Doubler           $doubler
     * @param null|RevealerInterface $revealer
     * @param null|StringUtil        $util
     */
    public function __construct(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Doubler $doubler = null, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\RevealerInterface $revealer = null, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Util\StringUtil $util = null)
    {
        if (null === $doubler) {
            $doubler = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\CachedDoubler();
            $doubler->registerClassPatch(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch\SplFileInfoPatch());
            $doubler->registerClassPatch(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch\TraversablePatch());
            $doubler->registerClassPatch(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch\ThrowablePatch());
            $doubler->registerClassPatch(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch\DisableConstructorPatch());
            $doubler->registerClassPatch(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch\ProphecySubjectPatch());
            $doubler->registerClassPatch(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch\ReflectionClassNewInstancePatch());
            $doubler->registerClassPatch(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch\HhvmExceptionPatch());
            $doubler->registerClassPatch(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch\MagicCallPatch());
            $doubler->registerClassPatch(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch\KeywordPatch());
        }
        $this->doubler = $doubler;
        $this->revealer = $revealer ?: new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\Revealer();
        $this->util = $util ?: new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Util\StringUtil();
    }
    /**
     * Creates new object prophecy.
     *
     * @param null|string $classOrInterface Class or interface name
     *
     * @return ObjectProphecy
     */
    public function prophesize($classOrInterface = null)
    {
        $this->prophecies[] = $prophecy = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Prophecy\ObjectProphecy(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\LazyDouble($this->doubler), new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Call\CallCenter($this->util), $this->revealer);
        if ($classOrInterface && \class_exists($classOrInterface)) {
            return $prophecy->willExtend($classOrInterface);
        }
        if ($classOrInterface && \interface_exists($classOrInterface)) {
            return $prophecy->willImplement($classOrInterface);
        }
        return $prophecy;
    }
    /**
     * Returns all created object prophecies.
     *
     * @return ObjectProphecy[]
     */
    public function getProphecies()
    {
        return $this->prophecies;
    }
    /**
     * Returns Doubler instance assigned to this Prophet.
     *
     * @return Doubler
     */
    public function getDoubler()
    {
        return $this->doubler;
    }
    /**
     * Checks all predictions defined by prophecies of this Prophet.
     *
     * @throws Exception\Prediction\AggregateException If any prediction fails
     */
    public function checkPredictions()
    {
        $exception = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Prediction\AggregateException("Some predictions failed:\n");
        foreach ($this->prophecies as $prophecy) {
            try {
                $prophecy->checkProphecyMethodsPredictions();
            } catch (\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Exception\Prediction\PredictionException $e) {
                $exception->append($e);
            }
        }
        if (\count($exception->getExceptions())) {
            throw $exception;
        }
    }
}
