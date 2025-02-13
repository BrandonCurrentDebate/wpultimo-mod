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

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Version;
class Manifest
{
    /** @var ApplicationName */
    private $name;
    /** @var Version */
    private $version;
    /** @var Type */
    private $type;
    /** @var CopyrightInformation */
    private $copyrightInformation;
    /** @var RequirementCollection */
    private $requirements;
    /** @var BundledComponentCollection */
    private $bundledComponents;
    public function __construct(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ApplicationName $name, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Version $version, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Type $type, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\CopyrightInformation $copyrightInformation, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\RequirementCollection $requirements, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\BundledComponentCollection $bundledComponents)
    {
        $this->name = $name;
        $this->version = $version;
        $this->type = $type;
        $this->copyrightInformation = $copyrightInformation;
        $this->requirements = $requirements;
        $this->bundledComponents = $bundledComponents;
    }
    public function getName() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ApplicationName
    {
        return $this->name;
    }
    public function getVersion() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Version
    {
        return $this->version;
    }
    public function getType() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Type
    {
        return $this->type;
    }
    public function getCopyrightInformation() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\CopyrightInformation
    {
        return $this->copyrightInformation;
    }
    public function getRequirements() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\RequirementCollection
    {
        return $this->requirements;
    }
    public function getBundledComponents() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\BundledComponentCollection
    {
        return $this->bundledComponents;
    }
    public function isApplication() : bool
    {
        return $this->type->isApplication();
    }
    public function isLibrary() : bool
    {
        return $this->type->isLibrary();
    }
    public function isExtension() : bool
    {
        return $this->type->isExtension();
    }
    public function isExtensionFor(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ApplicationName $application, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Version $version = null) : bool
    {
        if (!$this->isExtension()) {
            return \false;
        }
        /** @var Extension $type */
        $type = $this->type;
        if ($version !== null) {
            return $type->isCompatibleWith($application, $version);
        }
        return $type->isExtensionFor($application);
    }
}
