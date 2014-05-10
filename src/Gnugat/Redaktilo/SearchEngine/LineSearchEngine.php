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
 * This strategy needs the content to be converted into an array of lines which
 * have been stripped from their line break.
 *
 * The match is done on the whole line.
 */
class LineSearchEngine implements SearchEngine
{
    /** {@inheritdoc} */
    public function has(File $file, $pattern)
    {
        $lines = $file->readlines();
        $lineNumbers = array_flip($lines);

        return isset($lineNumbers[$pattern]);
    }

    /** {@inheritdoc} */
    public function findNext(File $file, $pattern)
    {

    }

    /** {@inheritdoc} */
    public function findPrevious(File $file, $pattern)
    {

    }

    /** {@inheritdoc} */
    public function supports($pattern)
    {
        if (!is_string($pattern)) {
            return false;
        }
        $hasNoLineBreak = (false === strpos($pattern, "\n"));

        return $hasNoLineBreak;
    }
}
