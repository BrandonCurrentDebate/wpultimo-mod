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

class AuthorCollection implements \Countable, \IteratorAggregate
{
    /** @var Author[] */
    private $authors = [];
    public function add(\WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\Author $author) : void
    {
        $this->authors[] = $author;
    }
    /**
     * @return Author[]
     */
    public function getAuthors() : array
    {
        return $this->authors;
    }
    public function count() : int
    {
        return \count($this->authors);
    }
    public function getIterator() : \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\AuthorCollectionIterator
    {
        return new \WP_Ultimo_Plugin_And_Theme_Manager\Dependencies\PharIo\Manifest\AuthorCollectionIterator($this);
    }
}
