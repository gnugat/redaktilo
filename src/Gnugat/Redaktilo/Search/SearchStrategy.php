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
     * Looks for the given pattern from the given line number ($location) to the
     * top of the File.
     * If no line number is given, the current line number of the file is used.
     * If the pattern doesn't match anything, returns false.
     *
     * @param File    $file
     * @param mixed   $pattern
     * @param integer $location
     *
     * @return mixed
     */
    public function findAbove(File $file, $pattern, $location = null);

    /**
     * Looks for the given pattern from the given line number ($location) to the
     * bottom of the File.
     * If no line number is given, the current line number of the file is used.
     * If the pattern doesn't match anything, returns false.
     *
     * @param File    $file
     * @param mixed   $pattern
     * @param integer $location
     *
     * @return mixed
     */
    public function findUnder(File $file, $pattern, $location = null);

    /**
     * @param mixed $pattern
     *
     * @return bool
     */
    public function supports($pattern);
}
