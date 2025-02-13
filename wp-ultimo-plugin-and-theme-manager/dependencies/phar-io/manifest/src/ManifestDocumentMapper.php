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

use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Exception as VersionException;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Version;
use WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionConstraintParser;
class ManifestDocumentMapper
{
    public function map(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocument $document) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Manifest
    {
        try {
            $contains = $document->getContainsElement();
            $type = $this->mapType($contains);
            $copyright = $this->mapCopyright($document->getCopyrightElement());
            $requirements = $this->mapRequirements($document->getRequiresElement());
            $bundledComponents = $this->mapBundledComponents($document);
            return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Manifest(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ApplicationName($contains->getName()), new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Version($contains->getVersion()), $type, $copyright, $requirements, $bundledComponents);
        } catch (\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Exception $e) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocumentMapperException($e->getMessage(), (int) $e->getCode(), $e);
        } catch (\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Exception $e) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocumentMapperException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
    private function mapType(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ContainsElement $contains) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Type
    {
        switch ($contains->getType()) {
            case 'application':
                return \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Type::application();
            case 'library':
                return \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Type::library();
            case 'extension':
                return $this->mapExtension($contains->getExtensionElement());
        }
        throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocumentMapperException(\sprintf('Unsupported type %s', $contains->getType()));
    }
    private function mapCopyright(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\CopyrightElement $copyright) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\CopyrightInformation
    {
        $authors = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\AuthorCollection();
        foreach ($copyright->getAuthorElements() as $authorElement) {
            $authors->add(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Author($authorElement->getName(), new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Email($authorElement->getEmail())));
        }
        $licenseElement = $copyright->getLicenseElement();
        $license = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\License($licenseElement->getType(), new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Url($licenseElement->getUrl()));
        return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\CopyrightInformation($authors, $license);
    }
    private function mapRequirements(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\RequiresElement $requires) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\RequirementCollection
    {
        $collection = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\RequirementCollection();
        $phpElement = $requires->getPHPElement();
        $parser = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionConstraintParser();
        try {
            $versionConstraint = $parser->parse($phpElement->getVersion());
        } catch (\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Exception $e) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocumentMapperException(\sprintf('Unsupported version constraint - %s', $e->getMessage()), (int) $e->getCode(), $e);
        }
        $collection->add(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\PhpVersionRequirement($versionConstraint));
        if (!$phpElement->hasExtElements()) {
            return $collection;
        }
        foreach ($phpElement->getExtElements() as $extElement) {
            $collection->add(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\PhpExtensionRequirement($extElement->getName()));
        }
        return $collection;
    }
    private function mapBundledComponents(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocument $document) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\BundledComponentCollection
    {
        $collection = new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\BundledComponentCollection();
        if (!$document->hasBundlesElement()) {
            return $collection;
        }
        foreach ($document->getBundlesElement()->getComponentElements() as $componentElement) {
            $collection->add(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\BundledComponent($componentElement->getName(), new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Version($componentElement->getVersion())));
        }
        return $collection;
    }
    private function mapExtension(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ExtensionElement $extension) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Extension
    {
        try {
            $versionConstraint = (new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\VersionConstraintParser())->parse($extension->getCompatible());
            return \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Type::extension(new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ApplicationName($extension->getFor()), $versionConstraint);
        } catch (\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Version\Exception $e) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocumentMapperException(\sprintf('Unsupported version constraint - %s', $e->getMessage()), (int) $e->getCode(), $e);
        }
    }
}
