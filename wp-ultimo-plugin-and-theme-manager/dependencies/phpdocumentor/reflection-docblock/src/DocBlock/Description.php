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
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Tags\Formatter\PassthroughFormatter;
use function vsprintf;
/**
 * Object representing to description for a DocBlock.
 *
 * A Description object can consist of plain text but can also include tags. A Description Formatter can then combine
 * a body template with sprintf-style placeholders together with formatted tags in order to reconstitute a complete
 * description text using the format that you would prefer.
 *
 * Because parsing a Description text can be a verbose process this is handled by the {@see DescriptionFactory}. It is
 * thus recommended to use that to create a Description object, like this:
 *
 *     $description = $descriptionFactory->create('This is a {@see Description}', $context);
 *
 * The description factory will interpret the given body and create a body template and list of tags from them, and pass
 * that onto the constructor if this class.
 *
 * > The $context variable is a class of type {@see \phpDocumentor\Reflection\Types\Context} and contains the namespace
 * > and the namespace aliases that apply to this DocBlock. These are used by the Factory to resolve and expand partial
 * > type names and FQSENs.
 *
 * If you do not want to use the DescriptionFactory you can pass a body template and tag listing like this:
 *
 *     $description = new Description(
 *         'This is a %1$s',
 *         [ new See(new Fqsen('\phpDocumentor\Reflection\DocBlock\Description')) ]
 *     );
 *
 * It is generally recommended to use the Factory as that will also apply escaping rules, while the Description object
 * is mainly responsible for rendering.
 *
 * @see DescriptionFactory to create a new Description.
 * @see Description\Formatter for the formatting of the body and tags.
 */
class Description
{
    /** @var string */
    private $bodyTemplate;
    /** @var Tag[] */
    private $tags;
    /**
     * Initializes a Description with its body (template) and a listing of the tags used in the body template.
     *
     * @param Tag[] $tags
     */
    public function __construct(string $bodyTemplate, array $tags = [])
    {
        $this->bodyTemplate = $bodyTemplate;
        $this->tags = $tags;
    }
    /**
     * Returns the body template.
     */
    public function getBodyTemplate() : string
    {
        return $this->bodyTemplate;
    }
    /**
     * Returns the tags for this DocBlock.
     *
     * @return Tag[]
     */
    public function getTags() : array
    {
        return $this->tags;
    }
    /**
     * Renders this description as a string where the provided formatter will format the tags in the expected string
     * format.
     */
    public function render(?\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Tags\Formatter $formatter = null) : string
    {
        if ($formatter === null) {
            $formatter = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\phpDocumentor\Reflection\DocBlock\Tags\Formatter\PassthroughFormatter();
        }
        $tags = [];
        foreach ($this->tags as $tag) {
            $tags[] = '{' . $formatter->format($tag) . '}';
        }
        return \vsprintf($this->bodyTemplate, $tags);
    }
    /**
     * Returns a plain string representation of this description.
     */
    public function __toString() : string
    {
        return $this->render();
    }
}
