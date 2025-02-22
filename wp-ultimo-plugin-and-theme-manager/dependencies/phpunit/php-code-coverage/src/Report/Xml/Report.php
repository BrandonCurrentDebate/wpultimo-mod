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

final class Report extends \SebastianBergmann\CodeCoverage\Report\Xml\File
{
    public function __construct(string $name)
    {
        $dom = new \DOMDocument();
        $dom->loadXML('<?xml version="1.0" ?><phpunit xmlns="https://schema.phpunit.de/coverage/1.0"><file /></phpunit>');
        $contextNode = $dom->getElementsByTagNameNS('https://schema.phpunit.de/coverage/1.0', 'file')->item(0);
        parent::__construct($contextNode);
        $this->setName($name);
    }
    public function asDom() : \DOMDocument
    {
        return $this->getDomDocument();
    }
    public function getFunctionObject($name) : \SebastianBergmann\CodeCoverage\Report\Xml\Method
    {
        $node = $this->getContextNode()->appendChild($this->getDomDocument()->createElementNS('https://schema.phpunit.de/coverage/1.0', 'function'));
        return new \SebastianBergmann\CodeCoverage\Report\Xml\Method($node, $name);
    }
    public function getClassObject($name) : \SebastianBergmann\CodeCoverage\Report\Xml\Unit
    {
        return $this->getUnitObject('class', $name);
    }
    public function getTraitObject($name) : \SebastianBergmann\CodeCoverage\Report\Xml\Unit
    {
        return $this->getUnitObject('trait', $name);
    }
    public function getSource() : \SebastianBergmann\CodeCoverage\Report\Xml\Source
    {
        $source = $this->getContextNode()->getElementsByTagNameNS('https://schema.phpunit.de/coverage/1.0', 'source')->item(0);
        if (!$source) {
            $source = $this->getContextNode()->appendChild($this->getDomDocument()->createElementNS('https://schema.phpunit.de/coverage/1.0', 'source'));
        }
        return new \SebastianBergmann\CodeCoverage\Report\Xml\Source($source);
    }
    private function setName($name) : void
    {
        $this->getContextNode()->setAttribute('name', \basename($name));
        $this->getContextNode()->setAttribute('path', \dirname($name));
    }
    private function getUnitObject($tagName, $name) : \SebastianBergmann\CodeCoverage\Report\Xml\Unit
    {
        $node = $this->getContextNode()->appendChild($this->getDomDocument()->createElementNS('https://schema.phpunit.de/coverage/1.0', $tagName));
        return new \SebastianBergmann\CodeCoverage\Report\Xml\Unit($node, $name);
    }
}
