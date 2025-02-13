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

use DOMDocument;
use DOMElement;
class ManifestDocument
{
    public const XMLNS = 'https://phar.io/xml/manifest/1.0';
    /** @var DOMDocument */
    private $dom;
    public static function fromFile(string $filename) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocument
    {
        if (!\file_exists($filename)) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocumentException(\sprintf('File "%s" not found', $filename));
        }
        return self::fromString(\file_get_contents($filename));
    }
    public static function fromString(string $xmlString) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocument
    {
        $prev = \libxml_use_internal_errors(\true);
        \libxml_clear_errors();
        $dom = new \DOMDocument();
        $dom->loadXML($xmlString);
        $errors = \libxml_get_errors();
        \libxml_use_internal_errors($prev);
        if (\count($errors) !== 0) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocumentLoadingException($errors);
        }
        return new self($dom);
    }
    private function __construct(\DOMDocument $dom)
    {
        $this->ensureCorrectDocumentType($dom);
        $this->dom = $dom;
    }
    public function getContainsElement() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ContainsElement
    {
        return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ContainsElement($this->fetchElementByName('contains'));
    }
    public function getCopyrightElement() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\CopyrightElement
    {
        return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\CopyrightElement($this->fetchElementByName('copyright'));
    }
    public function getRequiresElement() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\RequiresElement
    {
        return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\RequiresElement($this->fetchElementByName('requires'));
    }
    public function hasBundlesElement() : bool
    {
        return $this->dom->getElementsByTagNameNS(self::XMLNS, 'bundles')->length === 1;
    }
    public function getBundlesElement() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\BundlesElement
    {
        return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\BundlesElement($this->fetchElementByName('bundles'));
    }
    private function ensureCorrectDocumentType(\DOMDocument $dom) : void
    {
        $root = $dom->documentElement;
        if ($root->localName !== 'phar' || $root->namespaceURI !== self::XMLNS) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocumentException('Not a phar.io manifest document');
        }
    }
    private function fetchElementByName(string $elementName) : \DOMElement
    {
        $element = $this->dom->getElementsByTagNameNS(self::XMLNS, $elementName)->item(0);
        if (!$element instanceof \DOMElement) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocumentException(\sprintf('Element %s missing', $elementName));
        }
        return $element;
    }
}
