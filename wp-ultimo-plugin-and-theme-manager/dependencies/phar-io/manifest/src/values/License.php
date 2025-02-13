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

class License
{
    /** @var string */
    private $name;
    /** @var Url */
    private $url;
    public function __construct(string $name, \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Url $url)
    {
        $this->name = $name;
        $this->url = $url;
    }
    public function getName() : string
    {
        return $this->name;
    }
    public function getUrl() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Url
    {
        return $this->url;
    }
}
