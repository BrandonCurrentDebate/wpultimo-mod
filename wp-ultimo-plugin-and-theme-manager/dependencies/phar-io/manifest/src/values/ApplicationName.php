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

class ApplicationName
{
    /** @var string */
    private $name;
    public function __construct(string $name)
    {
        $this->ensureValidFormat($name);
        $this->name = $name;
    }
    public function asString() : string
    {
        return $this->name;
    }
    public function isEqual(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\ApplicationName $name) : bool
    {
        return $this->name === $name->name;
    }
    private function ensureValidFormat(string $name) : void
    {
        if (!\preg_match('#\\w/\\w#', $name)) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\InvalidApplicationNameException(\sprintf('Format of name "%s" is not valid - expected: vendor/packagename', $name), \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\InvalidApplicationNameException::InvalidFormat);
        }
    }
}
