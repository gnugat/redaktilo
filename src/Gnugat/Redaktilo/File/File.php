<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\File;

/**
 * A data source which contains:
 *
 * + the path to the file
 * + the raw content
 *
 * Its read and write methods provide a representation of the content which
 * depends on implementations of this interface:
 *
 * + an array of lines
 * + an array of PHP tokens
 * + etc...
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
interface File
{
    /**
     * Returns the file's path.
     *
     * @return string
     */
    public function getFilename();

    /**
     * Returns the file's raw content.
     *
     * @return string
     */
    public function getContent();

    /**
     * Returns a representation of the file's content.
     *
     * @return mixed
     */
    public function read();

    /**
     * Replaces the representation of the file's content.
     *
     * @param mixed $content
     */
    public function write($content);
}
