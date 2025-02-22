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
/**
 * Remove method functionality from the double which will clash with php keywords.
 *
 * @author Milan Magudia <milan@magudia.com>
 */
class KeywordPatch implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\ClassPatch\ClassPatchInterface
{
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
     * Remove methods that clash with php keywords
     *
     * @param ClassNode $node
     */
    public function apply(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Prophecy\Doubler\Generator\Node\ClassNode $node)
    {
        $methodNames = \array_keys($node->getMethods());
        $methodsToRemove = \array_intersect($methodNames, $this->getKeywords());
        foreach ($methodsToRemove as $methodName) {
            $node->removeMethod($methodName);
        }
    }
    /**
     * Returns patch priority, which determines when patch will be applied.
     *
     * @return int Priority number (higher - earlier)
     */
    public function getPriority()
    {
        return 49;
    }
    /**
     * Returns array of php keywords.
     *
     * @return array
     */
    private function getKeywords()
    {
        return ['__halt_compiler'];
    }
}
