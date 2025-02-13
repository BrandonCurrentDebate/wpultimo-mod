<?php

declare (strict_types=1);
/*
 * This file is part of PharIo\Version.
 *
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version;

class GreaterThanOrEqualToVersionConstraint extends \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\AbstractVersionConstraint
{
    /** @var Version */
    private $minimalVersion;
    public function __construct(string $originalValue, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Version $minimalVersion)
    {
        parent::__construct($originalValue);
        $this->minimalVersion = $minimalVersion;
    }
    public function complies(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Version $version) : bool
    {
        return $version->getVersionString() === $this->minimalVersion->getVersionString() || $version->isGreaterThan($this->minimalVersion);
    }
}
