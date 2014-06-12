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

    /** {@inheritdoc} */
    public function has(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $found = $this->findIn($lines, $pattern);

        return (false !== $found);
    }

    /** {@inheritdoc} */
    public function findNext(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $currentLineNumber = $file->getCurrentLineNumber() + 1;
        $nextLines = array_slice($lines, $currentLineNumber, null, true);

        return $this->findIn($nextLines, $pattern);
    }

    /** {@inheritdoc} */
    public function findPrevious(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $currentLineNumber = $file->getCurrentLineNumber() - 1;
        $previousLines = array_slice($lines, 0, $currentLineNumber, true);
        $reversedPreviousLines = array_reverse($previousLines, true);

        return $this->findIn($reversedPreviousLines, $pattern);
    }

    private function findIn(array $collection, $pattern)
    {
        return array_search($pattern, $collection, true);
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
