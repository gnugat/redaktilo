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
 * This strategy manipulates lines stripped of their line break character.
 *
 * The match is done on the whole line.
 *
 * @api
 */
class LineSearchStrategy implements SearchStrategy
{
    /** @var LineContentConverter */
    private $converter;

    /** @param LineContentConverter $converter */
    public function __construct(LineContentConverter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Checks if the given line is present at least once in the file.
     *
     * @param File   $file
     * @param string $pattern
     *
     * @return bool
     *
     * @api
     */
    public function has(File $file, $pattern)
    {
        $lines = $this->converter->from($file);

        return in_array($pattern, $lines, true);
    }

    /**
     * Returns the number of the given line if it is present after the current
     * one.
     *
     * @param File   $file
     * @param string $pattern
     *
     * @return integer
     *
     * @throws PatternNotFoundException If the line hasn't be found after the current one
     */
    public function findNext(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $currentLineNumber = $file->getCurrentLineNumber() + 1;
        $nextLines = array_slice($lines, $currentLineNumber, null, true);
        $foundLineNumber = array_search($pattern, $nextLines, true);
        if (false === $foundLineNumber) {
            throw new PatternNotFoundException($file, $pattern);
        }

        return $foundLineNumber;
    }

    /**
     * Returns the number of the given line if it is present before the current
     * one.
     *
     * @param File   $file
     * @param string $pattern
     *
     * @return integer
     *
     * @throws PatternNotFoundException If the line hasn't be found before the current one
     */
    public function findPrevious(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $currentLineNumber = $file->getCurrentLineNumber() - 1;
        $previousLines = array_slice($lines, 0, $currentLineNumber, true);
        $reversedPreviousLines = array_reverse($previousLines, true);
        $foundLineNumber = array_search($pattern, $reversedPreviousLines, true);
        if (false === $foundLineNumber) {
            throw new PatternNotFoundException($file, $pattern);
        }

        return $foundLineNumber;
    }

    /**
     * @param mixed $pattern
     *
     * @return bool
     *
     * @api
     */
    public function supports($pattern)
    {
        if (!is_string($pattern)) {
            return false;
        }
        $hasNoLineBreak = (false === strpos($pattern, "\n"));

        return $hasNoLineBreak;
    }
}
