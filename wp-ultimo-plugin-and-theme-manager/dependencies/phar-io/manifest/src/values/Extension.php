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
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionConstraint;
class Extension extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Type
{
    /** @var ApplicationName */
    private $application;
    /** @var VersionConstraint */
    private $versionConstraint;
    public function __construct(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ApplicationName $application, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionConstraint $versionConstraint)
    {
        $this->application = $application;
        $this->versionConstraint = $versionConstraint;
    }
    public function getApplicationName() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ApplicationName
    {
        return $this->application;
    }
    public function getVersionConstraint() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionConstraint
    {
        return $this->versionConstraint;
    }
    public function isExtension() : bool
    {
        return \true;
    }
    public function isExtensionFor(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ApplicationName $name) : bool
    {
        return $this->application->isEqual($name);
    }
    public function isCompatibleWith(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ApplicationName $name, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Version $version) : bool
    {
        return $this->isExtensionFor($name) && $this->versionConstraint->complies($version);
    }
}
