<?php

declare (strict_types=1);
/*
 * This file is part of the php-code-coverage package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\CodeCoverage\Report\Xml;

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\NamespaceUri;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Tokenizer;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\XMLSerializer;
final class Source
{
    /** @var \DOMElement */
    private $context;
    public function __construct(\DOMElement $context)
    {
        $this->context = $context;
    }
    public function setSourceCode(string $source) : void
    {
        $context = $this->context;
        $tokens = (new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\Tokenizer())->parse($source);
        $srcDom = (new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\XMLSerializer(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\TheSeer\Tokenizer\NamespaceUri($context->namespaceURI)))->toDom($tokens);
        $context->parentNode->replaceChild($context->ownerDocument->importNode($srcDom->documentElement, \true), $context);
    }
}
