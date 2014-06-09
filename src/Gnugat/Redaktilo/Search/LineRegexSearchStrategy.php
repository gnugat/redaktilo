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
 * The match is done using a regex on the whole line.
 *
 * @api
 */
class LineRegexSearchStrategy implements SearchStrategy
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
        $found = preg_grep($pattern, $lines);

        return count($found) > 0;
    }

    /** {@inheritdoc} */
    public function findNext(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $currentLineNumber = $file->getCurrentLineNumber() + 1;
        $nextLines = array_slice($lines, $currentLineNumber, null, true);
        $found = preg_grep($pattern, $nextLines);
        if (count($found) < 1) {
            return false;
        }
        reset($found);

        return key($found);
    }

    /** {@inheritdoc} */
    public function findPrevious(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $currentLineNumber = $file->getCurrentLineNumber() - 1;
        $previousLines = array_slice($lines, 0, $currentLineNumber, true);
        $found = preg_grep($pattern, $previousLines);
        if (count($found) < 1) {
            return false;
        }
        end($found);

        return key($found);
    }

    /** {@inheritdoc} */
    public function supports($pattern)
    {
        if (!is_string($pattern)) {
            return false;
        }

        return !(false === @preg_match($pattern, ''));
    }
}
