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

use Gnugat\Redaktilo\Converter\LineContentConverter;
use Gnugat\Redaktilo\File;

/**
 * This strategy manipulates directly line numbers:
 *
 * + `has` checks if the line exists
 * + `findNext` increments the current line number
 * + `findPrevious` decrements the current line number
 */
class LineNumberSearchStrategy implements SearchStrategy
{
    /** @var LineContentConverter */
    private $converter;

    /** @param LineContentConverter */
    public function __construct(LineContentConverter $converter)
    {
        $this->converter = $converter;
    }

    /** {@inheritdoc} */
    public function has(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $totalLines = count($lines);

        return 0 <= $pattern && $pattern < $totalLines;
    }

    /** {@inheritdoc} */
    public function findNext(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $totalLines = count($lines);
        $currentLineNumber = $file->getCurrentLineNumber();
        $foundLineNumber = $currentLineNumber + $pattern;
        if (0 > $foundLineNumber || $foundLineNumber >= $totalLines) {
            throw new PatternNotFoundException($file, $pattern);
        }

        return $foundLineNumber;
    }

    /** {@inheritdoc} */
    public function findPrevious(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $totalLines = count($lines);
        $currentLineNumber = $file->getCurrentLineNumber();
        $foundLineNumber = $currentLineNumber - $pattern;
        if (0 > $foundLineNumber || $foundLineNumber >= $totalLines) {
            throw new PatternNotFoundException($file, $pattern);
        }

        return $foundLineNumber;
    }

    /** {@inheritdoc} */
    public function supports($pattern)
    {
        return (is_int($pattern) && $pattern >= 0);
    }
}
