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
 * This strategy needs the content to be converted into an array of lines which
 * have been stripped from their line break.
 *
 * The match is done on the whole line.
 */
class LineSearchStrategy implements SearchStrategy
{
    /** {@inheritdoc} */
    public function has(File $file, $pattern)
    {
        $lines = $file->readlines();

        return in_array($pattern, $lines, true);
    }

    /** {@inheritdoc} */
    public function findNext(File $file, $pattern)
    {
        $lines = $file->readlines();
        $currentLineNumber = $file->getCurrentLineNumber() + 1;
        $nextLines = array_slice($lines, $currentLineNumber, null, true);
        $foundLineNumber = array_search($pattern, $nextLines, true);
        if (false === $foundLineNumber) {
            throw new PatternNotFoundException($file, $pattern);
        }

        return $foundLineNumber;
    }

    /** {@inheritdoc} */
    public function findPrevious(File $file, $pattern)
    {
        $lines = $file->readlines();
        $currentLineNumber = $file->getCurrentLineNumber() - 1;
        $previousLines = array_slice($lines, 0, $currentLineNumber, true);
        $reversedPreviousLines = array_reverse($previousLines, true);
        $foundLineNumber = array_search($pattern, $reversedPreviousLines, true);
        if (false === $foundLineNumber) {
            throw new PatternNotFoundException($file, $pattern);
        }

        return $foundLineNumber;
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
