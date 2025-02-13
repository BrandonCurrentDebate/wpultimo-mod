<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ArgumentNode;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ClassNode;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\MethodNode;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\PhpDocumentor\ClassAndInterfaceTagRetriever;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\PhpDocumentor\MethodTagRetrieverInterface;
/**
 * Discover Magical API using "@method" PHPDoc format.
 *
 * @author Thomas Tourlourat <thomas@tourlourat.com>
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class MagicCallPatch implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch\ClassPatchInterface
{
    const MAGIC_METHODS_WITH_ARGUMENTS = ['__call', '__callStatic', '__get', '__isset', '__set', '__set_state', '__unserialize', '__unset'];
    private $tagRetriever;
    public function __construct(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\PhpDocumentor\MethodTagRetrieverInterface $tagRetriever = null)
    {
        $this->tagRetriever = null === $tagRetriever ? new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\PhpDocumentor\ClassAndInterfaceTagRetriever() : $tagRetriever;
    }
    /**
     * Support any class
     *
     * @param ClassNode $node
     *
     * @return boolean
     */
    public function supports(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ClassNode $node)
    {
        return \true;
    }
    /**
     * Discover Magical API
     *
     * @param ClassNode $node
     */
    public function apply(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ClassNode $node)
    {
        $types = \array_filter($node->getInterfaces(), function ($interface) {
            return 0 !== \strpos($interface, 'Prophecy\\');
        });
        $types[] = $node->getParentClass();
        foreach ($types as $type) {
            $reflectionClass = new \ReflectionClass($type);
            while ($reflectionClass) {
                $tagList = $this->tagRetriever->getTagList($reflectionClass);
                foreach ($tagList as $tag) {
                    $methodName = $tag->getMethodName();
                    if (empty($methodName)) {
                        continue;
                    }
                    if (!$reflectionClass->hasMethod($methodName)) {
                        $methodNode = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\MethodNode($methodName);
                        // only magic methods can have a contract that needs to be enforced
                        if (\in_array($methodName, self::MAGIC_METHODS_WITH_ARGUMENTS)) {
                            foreach ($tag->getArguments() as $argument) {
                                $argumentNode = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ArgumentNode($argument['name']);
                                $methodNode->addArgument($argumentNode);
                            }
                        }
                        $methodNode->setStatic($tag->isStatic());
                        $node->addMethod($methodNode);
                    }
                }
                $reflectionClass = $reflectionClass->getParentClass();
            }
        }
    }
    /**
     * Returns patch priority, which determines when patch will be applied.
     *
     * @return integer Priority number (higher - earlier)
     */
    public function getPriority()
    {
        return 50;
    }
}
