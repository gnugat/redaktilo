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
     * Checks the pattern's presence in the whole File's content.
     *
     * @param File  $file
     * @param mixed $pattern
     *
     * @return bool
     */
    public function has(File $file, $pattern);

    /**
     * Starts the search from the File's cursor to the bottom and returns the
     * found location. If the pattern doesn't match anything, returns false.
     *
     * @param File  $file
     * @param mixed $pattern
     *
     * @return mixed
     */
    public function findNext(File $file, $pattern);

    /**
     * Starts the search from the File's cursor to the top and returns the found
     * location. If the pattern doesn't match anything, returns false.
     *
     * @param File  $file
     * @param mixed $pattern
     *
     * @return mixed
     */
    public function findPrevious(File $file, $pattern);

    /**
     * @param mixed $pattern
     *
     * @return bool
     */
    public function supports($pattern);
}
