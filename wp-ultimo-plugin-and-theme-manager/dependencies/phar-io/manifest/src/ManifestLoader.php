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

class ManifestLoader
{
    public static function fromFile(string $filename) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Manifest
    {
        try {
            return (new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocumentMapper())->map(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocument::fromFile($filename));
        } catch (\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Exception $e) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestLoaderException(\sprintf('Loading %s failed.', $filename), (int) $e->getCode(), $e);
        }
    }
    public static function fromPhar(string $filename) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Manifest
    {
        return self::fromFile('phar://' . $filename . '/manifest.xml');
    }
    public static function fromString(string $manifest) : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Manifest
    {
        try {
            return (new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocumentMapper())->map(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestDocument::fromString($manifest));
        } catch (\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Exception $e) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ManifestLoaderException('Processing string failed', (int) $e->getCode(), $e);
        }
    }
}
