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

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Fqsen;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Type;
/**
 * Value Object representing the type 'string'.
 *
 * @psalm-immutable
 */
final class InterfaceString implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Type
{
    /** @var Fqsen|null */
    private $fqsen;
    /**
     * Initializes this representation of a class string with the given Fqsen.
     */
    public function __construct(?\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Fqsen $fqsen = null)
    {
        $this->fqsen = $fqsen;
    }
    /**
     * Returns the FQSEN associated with this object.
     */
    public function getFqsen() : ?\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Fqsen
    {
        return $this->fqsen;
    }
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString() : string
    {
        if ($this->fqsen === null) {
            return 'interface-string';
        }
        return 'interface-string<' . (string) $this->fqsen . '>';
    }
}
