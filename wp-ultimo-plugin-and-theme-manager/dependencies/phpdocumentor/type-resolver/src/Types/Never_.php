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
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Types;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Type;
/**
 * Value Object representing the return-type 'never'.
 *
 * Never is generally only used when working with return types as it signifies that the method that only
 * ever throw or exit.
 *
 * @psalm-immutable
 */
final class Never_ implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Type
{
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString() : string
    {
        return 'never';
    }
}
