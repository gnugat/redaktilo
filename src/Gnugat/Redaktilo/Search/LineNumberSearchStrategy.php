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
 * Moves x lines above or under the current one.
 */
class LineNumberSearchStrategy implements SearchStrategy
{
    /** @param LineContentConverter $converter */
    public function __construct(LineContentConverter $converter)
    {
        $this->converter = $converter;
    }

    /** {@inheritdoc} */
    public function findAbove(File $file, $pattern, $before = null)
    {
        $foundLineNumber = ($before ?: $file->getCurrentLineNumber()) - $pattern;
        $lines = $this->converter->from($file);
        $totalLines = count($lines);
        if (0 > $foundLineNumber || $foundLineNumber >= $totalLines) {
            return false;
        }

        return $foundLineNumber;
    }

    /** {@inheritdoc} */
    public function findUnder(File $file, $pattern, $after = null)
    {
        $foundLineNumber = ($after ?: $file->getCurrentLineNumber()) + $pattern;
        $lines = $this->converter->from($file);
        $totalLines = count($lines);
        if (0 > $foundLineNumber || $foundLineNumber >= $totalLines) {
            return false;
        }

        return $foundLineNumber;
    }

    /** {@inheritdoc} */
    public function supports($pattern)
    {
        return (is_int($pattern) && $pattern >= 0);
    }
}
