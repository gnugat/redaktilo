<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\SearchEngine;

use Gnugat\Redaktilo\File;

/**
 * A lookup strategy supporting only one kind of pattern.
 *
 * @api
 */
interface SearchEngine
{
    /**
     * Checks the presence in the whole File's content.
     *
     * @param File  $file
     * @param mixed $pattern
     *
     * @return bool
     */
    public function has(File $file, $pattern);

    /**
     * Starts the search from the File's cursor to the bottom.
     *
     * @param File  $file
     * @param mixed $pattern
     *
     * @return mixed
     *
     * @api
     */
    public function findNext(File $file, $pattern);

    /**
     * Starts the search from the File's cursor to the top.
     *
     * @param File  $file
     * @param mixed $pattern
     *
     * @return mixed
     *
     * @api
     */
    public function findPrevious(File $file, $pattern);

    /**
     * @param mixed $pattern
     *
     * @return bool
     *
     * @api
     */
    public function supports($pattern);
}
