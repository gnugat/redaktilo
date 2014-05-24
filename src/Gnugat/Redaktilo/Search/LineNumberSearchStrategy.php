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
 * This strategy manipulates directly line numbers.
 *
 * @api
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

    /**
     * Checks if the file has more lines than the given number.
     *
     * @param File    $file
     * @param integer $pattern
     *
     * @return bool
     *
     * @api
     */
    public function has(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $totalLines = count($lines);

        return 0 <= $pattern && $pattern < $totalLines;
    }

    /**
     * Increments the current line number.
     *
     * @param File    $file
     * @param integer $pattern
     *
     * @return integer
     *
     * @api
     */
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

    /**
     * Decrements the current line number.
     *
     * @param File    $file
     * @param integer $pattern
     *
     * @return integer
     *
     * @api
     */
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

    /**
     * @param mixed $pattern
     *
     * @return bool
     *
     * @api
     */
    public function supports($pattern)
    {
        return (is_int($pattern) && $pattern >= 0);
    }
}
