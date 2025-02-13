<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\PhpDocumentor;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Tags\Method;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlockFactory;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Types\ContextFactory;
/**
 * @author Théo FIDRY <theo.fidry@gmail.com>
 *
 * @internal
 */
final class ClassTagRetriever implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\PhpDocumentor\MethodTagRetrieverInterface
{
    private $docBlockFactory;
    private $contextFactory;
    public function __construct()
    {
        $this->docBlockFactory = \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlockFactory::createInstance();
        $this->contextFactory = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Types\ContextFactory();
    }
    /**
     * @param \ReflectionClass $reflectionClass
     *
     * @return Method[]
     */
    public function getTagList(\ReflectionClass $reflectionClass)
    {
        try {
            $phpdoc = $this->docBlockFactory->create($reflectionClass, $this->contextFactory->createFromReflector($reflectionClass));
            $methods = array();
            foreach ($phpdoc->getTagsByName('method') as $tag) {
                if ($tag instanceof \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Tags\Method) {
                    $methods[] = $tag;
                }
            }
            return $methods;
        } catch (\InvalidArgumentException $e) {
            return array();
        }
    }
}
