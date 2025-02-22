<?php

declare (strict_types=1);
/*
 * This file is part of PharIo\Manifest.
 *
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest;

use DOMElement;
use DOMNodeList;
class ManifestElement
{
    public const XMLNS = 'https://phar.io/xml/manifest/1.0';
    /** @var DOMElement */
    private $element;
    public function __construct(\DOMElement $element)
    {
        $this->element = $element;
    }
    protected function getAttributeValue(string $name) : string
    {
        if (!$this->element->hasAttribute($name)) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestElementException(\sprintf('Attribute %s not set on element %s', $name, $this->element->localName));
        }
        return $this->element->getAttribute($name);
    }
    protected function getChildByName(string $elementName) : \DOMElement
    {
        $element = $this->element->getElementsByTagNameNS(self::XMLNS, $elementName)->item(0);
        if (!$element instanceof \DOMElement) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestElementException(\sprintf('Element %s missing', $elementName));
        }
        return $element;
    }
    protected function getChildrenByName(string $elementName) : \DOMNodeList
    {
        $elementList = $this->element->getElementsByTagNameNS(self::XMLNS, $elementName);
        if ($elementList->length === 0) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestElementException(\sprintf('Element(s) %s missing', $elementName));
        }
        return $elementList;
    }
    protected function hasChild(string $elementName) : bool
    {
        return $this->element->getElementsByTagNameNS(self::XMLNS, $elementName)->length !== 0;
    }
}
