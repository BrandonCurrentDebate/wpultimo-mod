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
/**
 * SplFileInfo patch.
 * Makes SplFileInfo and derivative classes usable with Prophecy.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class SplFileInfoPatch implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch\ClassPatchInterface
{
    /**
     * Supports everything that extends SplFileInfo.
     *
     * @param ClassNode $node
     *
     * @return bool
     */
    public function supports(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ClassNode $node)
    {
        if (null === $node->getParentClass()) {
            return \false;
        }
        return 'SplFileInfo' === $node->getParentClass() || \is_subclass_of($node->getParentClass(), 'SplFileInfo');
    }
    /**
     * Updated constructor code to call parent one with dummy file argument.
     *
     * @param ClassNode $node
     */
    public function apply(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ClassNode $node)
    {
        if ($node->hasMethod('__construct')) {
            $constructor = $node->getMethod('__construct');
        } else {
            $constructor = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\MethodNode('__construct');
            $node->addMethod($constructor);
        }
        if ($this->nodeIsDirectoryIterator($node)) {
            $constructor->setCode('return parent::__construct("' . __DIR__ . '");');
            return;
        }
        if ($this->nodeIsSplFileObject($node)) {
            $filePath = \str_replace('\\', '\\\\', __FILE__);
            $constructor->setCode('return parent::__construct("' . $filePath . '");');
            return;
        }
        if ($this->nodeIsSymfonySplFileInfo($node)) {
            $filePath = \str_replace('\\', '\\\\', __FILE__);
            $constructor->setCode('return parent::__construct("' . $filePath . '", "", "");');
            return;
        }
        $constructor->useParentCode();
    }
    /**
     * Returns patch priority, which determines when patch will be applied.
     *
     * @return int Priority number (higher - earlier)
     */
    public function getPriority()
    {
        return 50;
    }
    /**
     * @param ClassNode $node
     * @return boolean
     */
    private function nodeIsDirectoryIterator(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ClassNode $node)
    {
        $parent = $node->getParentClass();
        return 'DirectoryIterator' === $parent || \is_subclass_of($parent, 'DirectoryIterator');
    }
    /**
     * @param ClassNode $node
     * @return boolean
     */
    private function nodeIsSplFileObject(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ClassNode $node)
    {
        $parent = $node->getParentClass();
        return 'SplFileObject' === $parent || \is_subclass_of($parent, 'SplFileObject');
    }
    /**
     * @param ClassNode $node
     * @return boolean
     */
    private function nodeIsSymfonySplFileInfo(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ClassNode $node)
    {
        $parent = $node->getParentClass();
        return 'Symfony\\Component\\Finder\\SplFileInfo' === $parent;
    }
}
