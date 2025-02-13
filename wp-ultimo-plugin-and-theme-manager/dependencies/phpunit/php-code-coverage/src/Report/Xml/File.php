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

class File
{
    /**
     * @var \DOMDocument
     */
    private $dom;
    /**
     * @var \DOMElement
     */
    private $contextNode;
    public function __construct(\DOMElement $context)
    {
        $this->dom = $context->ownerDocument;
        $this->contextNode = $context;
    }
    public function getTotals() : \SebastianBergmann\CodeCoverage\Report\Xml\Totals
    {
        $totalsContainer = $this->contextNode->firstChild;
        if (!$totalsContainer) {
            $totalsContainer = $this->contextNode->appendChild($this->dom->createElementNS('https://schema.phpunit.de/coverage/1.0', 'totals'));
        }
        return new \SebastianBergmann\CodeCoverage\Report\Xml\Totals($totalsContainer);
    }
    public function getLineCoverage(string $line) : \SebastianBergmann\CodeCoverage\Report\Xml\Coverage
    {
        $coverage = $this->contextNode->getElementsByTagNameNS('https://schema.phpunit.de/coverage/1.0', 'coverage')->item(0);
        if (!$coverage) {
            $coverage = $this->contextNode->appendChild($this->dom->createElementNS('https://schema.phpunit.de/coverage/1.0', 'coverage'));
        }
        $lineNode = $coverage->appendChild($this->dom->createElementNS('https://schema.phpunit.de/coverage/1.0', 'line'));
        return new \SebastianBergmann\CodeCoverage\Report\Xml\Coverage($lineNode, $line);
    }
    protected function getContextNode() : \DOMElement
    {
        return $this->contextNode;
    }
    protected function getDomDocument() : \DOMDocument
    {
        return $this->dom;
    }
}
