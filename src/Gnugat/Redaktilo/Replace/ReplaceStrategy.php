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
     * @param mixed $location
     *
     * @return bool
     */
    public function supports($location);

    /**
     * @param File  $file
     * @param mixed $location
     * @param mixed $replacement
     *
     * @api
     */
    public function replaceWith(File $file, $location, $replacement);

    /**
     * @param File  $file
     * @param mixed $location
     */
    public function removeAt(File $file, $location);

    /**
     * @param File  $file
     * @param mixed $location
     * @param mixed $addition
     */
    public function insertAt(File $file, $location, $addition);
}
