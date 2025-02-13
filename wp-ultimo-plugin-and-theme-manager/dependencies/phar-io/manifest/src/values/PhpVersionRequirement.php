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

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionConstraint;
class PhpVersionRequirement implements \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Requirement
{
    /** @var VersionConstraint */
    private $versionConstraint;
    public function __construct(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionConstraint $versionConstraint)
    {
        $this->versionConstraint = $versionConstraint;
    }
    public function getVersionConstraint() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionConstraint
    {
        return $this->versionConstraint;
    }
}
