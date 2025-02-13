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

class ContainsElement extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestElement
{
    public function getName() : string
    {
        return $this->getAttributeValue('name');
    }
    public function getVersion() : string
    {
        return $this->getAttributeValue('version');
    }
    public function getType() : string
    {
        return $this->getAttributeValue('type');
    }
    public function getExtensionElement() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ExtensionElement
    {
        return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ExtensionElement($this->getChildByName('extension'));
    }
}
