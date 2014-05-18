<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Replace;

use Gnugat\Redaktilo\File;

/**
 * @api
 */
interface ReplaceStrategy
{
    /**
     * @param mixed $pattern
     *
     * @return bool
     */
    public function supports($pattern);

    /**
     * @param File  $file
     * @param mixed $pattern
     * @param mixed $replacement
     *
     * @api
     */
    public function replaceWith(File $file, $pattern, $replacement);

    /**
     * @param File  $file
     * @param mixed $pattern
     */
    public function removeAt(File $file, $pattern);

    /**
     * @param File  $file
     * @param mixed $pattern
     * @param mixed $addition
     */
    public function insertAt(File $file, $pattern, $addition);
}
