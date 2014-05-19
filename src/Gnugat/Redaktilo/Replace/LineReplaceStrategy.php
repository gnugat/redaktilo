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

class LineReplaceStrategy implements ReplaceStrategy
{
    /** {@inheritdoc} */
    public function supports($location)
    {
        return (is_int($location) && $location >= 0);
    }

    /** {@inheritdoc} */
    public function removeAt(File $file, $location)
    {
        $lines = $file->readlines();
        unset($lines[$location]);
        $file->writelines($lines);
    }

    /** {@inheritdoc} */
    public function replaceWith(File $file, $location, $replacement)
    {
        $lines = $file->readlines();
        $lines[$location] = $replacement;
        $file->writelines($lines);
    }

    /** {@inheritdoc} */
    public function insertAt(File $file, $location, $addition)
    {
        $lines = $file->readlines();
        array_splice($lines, $location, 0, $addition);
        $file->writelines($lines);
    }
}
