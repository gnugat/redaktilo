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
 * Allows File manipulations.
 *
 * Generally delegates read and write operations to Filesystem.
 * An Editor can hold many File, if necessary.
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
     * Inserts $add after $after in the file.
     * Does also write operations if `autosave` is enabled.
     *
     * @param mixed $add
     * @param mixed $after
     */
    public function addAfter($add, $after);
}
