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

class CopyrightInformation
{
    /** @var AuthorCollection */
    private $authors;
    /** @var License */
    private $license;
    public function __construct(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\AuthorCollection $authors, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\License $license)
    {
        $this->authors = $authors;
        $this->license = $license;
    }
    public function getAuthors() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\AuthorCollection
    {
        return $this->authors;
    }
    public function getLicense() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\License
    {
        return $this->license;
    }
}
