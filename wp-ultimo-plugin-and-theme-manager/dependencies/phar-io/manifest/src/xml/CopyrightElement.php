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

class CopyrightElement extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestElement
{
    public function getAuthorElements() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\AuthorElementCollection
    {
        return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\AuthorElementCollection($this->getChildrenByName('author'));
    }
    public function getLicenseElement() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\LicenseElement
    {
        return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\LicenseElement($this->getChildByName('license'));
    }
}
