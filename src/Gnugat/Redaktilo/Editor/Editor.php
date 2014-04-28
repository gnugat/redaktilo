<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Editor;

/**
 * Allows File manipulations:
 *
 * + open an existing file
 * + move the cursor to the desired area
 * + insert whatever you want around the cursor
 * + save your modifications
 *
 * Generally delegates read and write operations to Filesystem.
 */
interface Editor
{
    /**
     * Opens an existing file.
     *
     * @param string $filename
     */
    public function open($filename);

    /**
     * Moves down the cursor until $to is encountered.
     *
     * @param mixed $to
     */
    public function jumpDownTo($to);

    /**
     * Moves up the cursor until $to is encountered.
     *
     * @param mixed $to
     */
    public function jumpUpTo($to);

    /**
     * Moves up the cursor and inserts $add.
     *
     * @param mixed $add
     */
    public function addBefore($add);

    /**
     * Moves down the cursor and inserts $add.
     *
     * @param mixed $add
     */
    public function addAfter($add);

    /**
     * Backups the modifications.
     */
    public function save();
}
