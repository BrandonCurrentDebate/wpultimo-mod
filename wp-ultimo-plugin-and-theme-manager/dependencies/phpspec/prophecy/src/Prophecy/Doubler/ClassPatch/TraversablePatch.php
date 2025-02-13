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

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ClassNode;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\MethodNode;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ReturnTypeNode;
/**
 * Traversable interface patch.
 * Forces classes that implement interfaces, that extend Traversable to also implement Iterator.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class TraversablePatch implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch\ClassPatchInterface
{
    /**
     * Supports nodetree, that implement Traversable, but not Iterator or IteratorAggregate.
     *
     * @param ClassNode $node
     *
     * @return bool
     */
    public function supports(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ClassNode $node)
    {
        if (\in_array('Iterator', $node->getInterfaces())) {
            return \false;
        }
        if (\in_array('IteratorAggregate', $node->getInterfaces())) {
            return \false;
        }
        foreach ($node->getInterfaces() as $interface) {
            if ('Traversable' !== $interface && !\is_subclass_of($interface, 'Traversable')) {
                continue;
            }
            if ('Iterator' === $interface || \is_subclass_of($interface, 'Iterator')) {
                continue;
            }
            if ('IteratorAggregate' === $interface || \is_subclass_of($interface, 'IteratorAggregate')) {
                continue;
            }
            return \true;
        }
        return \false;
    }
    /**
     * Forces class to implement Iterator interface.
     *
     * @param ClassNode $node
     */
    public function apply(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ClassNode $node)
    {
        $node->addInterface('Iterator');
        $currentMethod = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\MethodNode('current');
        \PHP_VERSION_ID >= 80100 && $currentMethod->setReturnTypeNode(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ReturnTypeNode('mixed'));
        $node->addMethod($currentMethod);
        $keyMethod = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\MethodNode('key');
        \PHP_VERSION_ID >= 80100 && $keyMethod->setReturnTypeNode(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ReturnTypeNode('mixed'));
        $node->addMethod($keyMethod);
        $nextMethod = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\MethodNode('next');
        \PHP_VERSION_ID >= 80100 && $nextMethod->setReturnTypeNode(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ReturnTypeNode('void'));
        $node->addMethod($nextMethod);
        $rewindMethod = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\MethodNode('rewind');
        \PHP_VERSION_ID >= 80100 && $rewindMethod->setReturnTypeNode(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ReturnTypeNode('void'));
        $node->addMethod($rewindMethod);
        $validMethod = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\MethodNode('valid');
        \PHP_VERSION_ID >= 80100 && $validMethod->setReturnTypeNode(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ReturnTypeNode('bool'));
        $node->addMethod($validMethod);
    }
    /**
     * Returns patch priority, which determines when patch will be applied.
     *
     * @return int Priority number (higher - earlier)
     */
    public function getPriority()
    {
        return 100;
    }
}
