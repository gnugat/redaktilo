<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\File;

/**
 * A lookup strategy supporting a specific kind of pattern.
 */
interface SearchStrategy
{
    /**
     * Looks for the given pattern from the given line number ($before) to the
     * top of the File.
     * If no line number is given, the current line number of the file is used.
     * If the pattern doesn't match anything, returns false.
     *
     * @param File    $file
     * @param mixed   $pattern
     * @param integer $before
     *
     * @return mixed
     */
    public function findPrevious(File $file, $pattern, $before = null);

    /**
     * Looks for the given pattern from the given line number ($after) to the
     * bottom of the File.
     * If no line number is given, the current line number of the file is used.
     * If the pattern doesn't match anything, returns false.
     *
     * @param File    $file
     * @param mixed   $pattern
     * @param integer $after
     *
     * @return mixed
     */
    public function findNext(File $file, $pattern, $after = null);

    /**
     * @param mixed $pattern
     *
     * @return bool
     */
    public function supports($pattern);
}
