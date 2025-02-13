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
abstract class Type
{
    public static function application() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Application
    {
        return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Application();
    }
    public static function library() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Library
    {
        return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Library();
    }
    public static function extension(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ApplicationName $application, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionConstraint $versionConstraint) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Extension
    {
        return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Extension($application, $versionConstraint);
    }
    /** @psalm-assert-if-true Application $this */
    public function isApplication() : bool
    {
        return \false;
    }
    /** @psalm-assert-if-true Library $this */
    public function isLibrary() : bool
    {
        return \false;
    }
    /** @psalm-assert-if-true Extension $this */
    public function isExtension() : bool
    {
        return \false;
    }
}
