<?php

declare (strict_types=1);
/**
 * This file is part of phpDocumentor.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 * @link http://phpdoc.org
 */
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Tags\Reference;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Fqsen as RealFqsen;
/**
 * Fqsen reference used by {@see \phpDocumentor\Reflection\DocBlock\Tags\See}
 */
final class Fqsen implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Tags\Reference\Reference
{
    /** @var RealFqsen */
    private $fqsen;
    public function __construct(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Fqsen $fqsen)
    {
        $this->fqsen = $fqsen;
    }
    /**
     * @return string string representation of the referenced fqsen
     */
    public function __toString() : string
    {
        return (string) $this->fqsen;
    }
}
