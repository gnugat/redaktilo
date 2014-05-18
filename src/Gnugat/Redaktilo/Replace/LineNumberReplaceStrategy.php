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

class LineNumberReplaceStrategy implements ReplaceStrategy
{
    /** {@inheritdoc} */
    public function supports($pattern)
    {
        return (is_int($pattern) && $pattern >= 0);
    }

    /** {@inheritdoc} */
    public function removeAt(File $file, $pattern)
    {
        $lines = $file->readlines();
        unset($lines[$pattern]);
        $file->writelines($lines);
    }

    /** {@inheritdoc} */
    public function replaceWith(File $file, $pattern, $replacement)
    {
        $lines = $file->readlines();
        $lines[$pattern] = $replacement;
        $file->writelines($lines);
    }

    /** {@inheritdoc} */
    public function insertAt(File $file, $pattern, $addition)
    {
        $lines = $file->readlines();
        array_splice($lines, $pattern, 0, $addition);
        $file->writelines($lines);
    }
}
