<?php

declare (strict_types=1);
/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link      http://phpdoc.org
 */
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Tags\Formatter;
interface Tag
{
    public function getName() : string;
    /**
     * @return Tag|mixed Class that implements Tag
     * @phpstan-return ?Tag
     */
    public static function create(string $body);
    public function render(?\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Tags\Formatter $formatter = null) : string;
    public function __toString() : string;
}
