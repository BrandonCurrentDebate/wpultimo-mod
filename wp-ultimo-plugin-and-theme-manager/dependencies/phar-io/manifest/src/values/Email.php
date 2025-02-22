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

class Email
{
    /** @var string */
    private $email;
    public function __construct(string $email)
    {
        $this->ensureEmailIsValid($email);
        $this->email = $email;
    }
    public function asString() : string
    {
        return $this->email;
    }
    private function ensureEmailIsValid(string $url) : void
    {
        if (\filter_var($url, \FILTER_VALIDATE_EMAIL) === \false) {
            throw new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\InvalidEmailException();
        }
    }
}
