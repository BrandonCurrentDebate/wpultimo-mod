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
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Tags;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Description;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Type;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\TypeResolver;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Types\Context as TypeContext;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Utils;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Webmozart\Assert\Assert;
use function array_shift;
use function array_unshift;
use function implode;
use function strpos;
use function substr;
use const PREG_SPLIT_DELIM_CAPTURE;
/**
 * Reflection class for the {@}param tag in a Docblock.
 */
final class Param extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Tags\TagWithType implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod
{
    /** @var string|null */
    private $variableName;
    /** @var bool determines whether this is a variadic argument */
    private $isVariadic;
    /** @var bool determines whether this is passed by reference */
    private $isReference;
    public function __construct(?string $variableName, ?\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Type $type = null, bool $isVariadic = \false, ?\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Description $description = null, bool $isReference = \false)
    {
        $this->name = 'param';
        $this->variableName = $variableName;
        $this->type = $type;
        $this->isVariadic = $isVariadic;
        $this->description = $description;
        $this->isReference = $isReference;
    }
    public static function create(string $body, ?\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\TypeResolver $typeResolver = null, ?\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\DescriptionFactory $descriptionFactory = null, ?\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Types\Context $context = null) : self
    {
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Webmozart\Assert\Assert::stringNotEmpty($body);
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Webmozart\Assert\Assert::notNull($typeResolver);
        \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Webmozart\Assert\Assert::notNull($descriptionFactory);
        [$firstPart, $body] = self::extractTypeFromBody($body);
        $type = null;
        $parts = \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\Utils::pregSplit('/(\\s+)/Su', $body, 2, \PREG_SPLIT_DELIM_CAPTURE);
        $variableName = '';
        $isVariadic = \false;
        $isReference = \false;
        // if the first item that is encountered is not a variable; it is a type
        if ($firstPart && !self::strStartsWithVariable($firstPart)) {
            $type = $typeResolver->resolve($firstPart, $context);
        } else {
            // first part is not a type; we should prepend it to the parts array for further processing
            \array_unshift($parts, $firstPart);
        }
        // if the next item starts with a $ or ...$ or &$ or &...$ it must be the variable name
        if (isset($parts[0]) && self::strStartsWithVariable($parts[0])) {
            $variableName = \array_shift($parts);
            if ($type) {
                \array_shift($parts);
            }
            \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\Webmozart\Assert\Assert::notNull($variableName);
            if (\strpos($variableName, '$') === 0) {
                $variableName = \substr($variableName, 1);
            } elseif (\strpos($variableName, '&$') === 0) {
                $isReference = \true;
                $variableName = \substr($variableName, 2);
            } elseif (\strpos($variableName, '...$') === 0) {
                $isVariadic = \true;
                $variableName = \substr($variableName, 4);
            } elseif (\strpos($variableName, '&...$') === 0) {
                $isVariadic = \true;
                $isReference = \true;
                $variableName = \substr($variableName, 5);
            }
        }
        $description = $descriptionFactory->create(\implode('', $parts), $context);
        return new static($variableName, $type, $isVariadic, $description, $isReference);
    }
    /**
     * Returns the variable's name.
     */
    public function getVariableName() : ?string
    {
        return $this->variableName;
    }
    /**
     * Returns whether this tag is variadic.
     */
    public function isVariadic() : bool
    {
        return $this->isVariadic;
    }
    /**
     * Returns whether this tag is passed by reference.
     */
    public function isReference() : bool
    {
        return $this->isReference;
    }
    /**
     * Returns a string representation for this tag.
     */
    public function __toString() : string
    {
        if ($this->description) {
            $description = $this->description->render();
        } else {
            $description = '';
        }
        $variableName = '';
        if ($this->variableName) {
            $variableName .= ($this->isReference ? '&' : '') . ($this->isVariadic ? '...' : '');
            $variableName .= '$' . $this->variableName;
        }
        $type = (string) $this->type;
        return $type . ($variableName !== '' ? ($type !== '' ? ' ' : '') . $variableName : '') . ($description !== '' ? ($type !== '' || $variableName !== '' ? ' ' : '') . $description : '');
    }
    private static function strStartsWithVariable(string $str) : bool
    {
        return \strpos($str, '$') === 0 || \strpos($str, '...$') === 0 || \strpos($str, '&$') === 0 || \strpos($str, '&...$') === 0;
    }
}
