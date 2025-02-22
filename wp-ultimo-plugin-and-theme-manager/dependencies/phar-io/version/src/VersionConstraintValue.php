<?php

declare (strict_types=1);
namespace WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version;

class VersionConstraintValue
{
    /** @var VersionNumber */
    private $major;
    /** @var VersionNumber */
    private $minor;
    /** @var VersionNumber */
    private $patch;
    /** @var string */
    private $label = '';
    /** @var string */
    private $buildMetaData = '';
    /** @var string */
    private $versionString = '';
    public function __construct(string $versionString)
    {
        $this->versionString = $versionString;
        $this->parseVersion($versionString);
    }
    public function getLabel() : string
    {
        return $this->label;
    }
    public function getBuildMetaData() : string
    {
        return $this->buildMetaData;
    }
    public function getVersionString() : string
    {
        return $this->versionString;
    }
    public function getMajor() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionNumber
    {
        return $this->major;
    }
    public function getMinor() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionNumber
    {
        return $this->minor;
    }
    public function getPatch() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionNumber
    {
        return $this->patch;
    }
    private function parseVersion(string $versionString) : void
    {
        $this->extractBuildMetaData($versionString);
        $this->extractLabel($versionString);
        $this->stripPotentialVPrefix($versionString);
        $versionSegments = \explode('.', $versionString);
        $this->major = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionNumber(\is_numeric($versionSegments[0]) ? (int) $versionSegments[0] : null);
        $minorValue = isset($versionSegments[1]) && \is_numeric($versionSegments[1]) ? (int) $versionSegments[1] : null;
        $patchValue = isset($versionSegments[2]) && \is_numeric($versionSegments[2]) ? (int) $versionSegments[2] : null;
        $this->minor = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionNumber($minorValue);
        $this->patch = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionNumber($patchValue);
    }
    private function extractBuildMetaData(string &$versionString) : void
    {
        if (\preg_match('/\\+(.*)/', $versionString, $matches) === 1) {
            $this->buildMetaData = $matches[1];
            $versionString = \str_replace($matches[0], '', $versionString);
        }
    }
    private function extractLabel(string &$versionString) : void
    {
        if (\preg_match('/-(.*)/', $versionString, $matches) === 1) {
            $this->label = $matches[1];
            $versionString = \str_replace($matches[0], '', $versionString);
        }
    }
    private function stripPotentialVPrefix(string &$versionString) : void
    {
        if ($versionString[0] !== 'v') {
            return;
        }
        $versionString = \substr($versionString, 1);
    }
}
