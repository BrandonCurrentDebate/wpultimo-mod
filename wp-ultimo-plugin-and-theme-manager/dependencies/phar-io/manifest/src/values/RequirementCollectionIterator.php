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

class RequirementCollectionIterator implements \Iterator
{
    /** @var Requirement[] */
    private $requirements;
    /** @var int */
    private $position = 0;
    public function __construct(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\RequirementCollection $requirements)
    {
        $this->requirements = $requirements->getRequirements();
    }
    public function rewind() : void
    {
        $this->position = 0;
    }
    public function valid() : bool
    {
        return $this->position < \count($this->requirements);
    }
    public function key() : int
    {
        return $this->position;
    }
    public function current() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Requirement
    {
        return $this->requirements[$this->position];
    }
    public function next() : void
    {
        $this->position++;
    }
}
