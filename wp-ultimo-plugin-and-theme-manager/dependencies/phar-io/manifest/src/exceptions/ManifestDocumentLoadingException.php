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

use LibXMLError;
class ManifestDocumentLoadingException extends \Exception implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Exception
{
    /** @var LibXMLError[] */
    private $libxmlErrors;
    /**
     * ManifestDocumentLoadingException constructor.
     *
     * @param LibXMLError[] $libxmlErrors
     */
    public function __construct(array $libxmlErrors)
    {
        $this->libxmlErrors = $libxmlErrors;
        $first = $this->libxmlErrors[0];
        parent::__construct(\sprintf('%s (Line: %d / Column: %d / File: %s)', $first->message, $first->line, $first->column, $first->file), $first->code);
    }
    /**
     * @return LibXMLError[]
     */
    public function getLibxmlErrors() : array
    {
        return $this->libxmlErrors;
    }
}
