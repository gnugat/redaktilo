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
 * Matches the lines which are exactly the same as the given pattern.
 */
class SameSearchStrategy extends LineSearchStrategy
{
    /** @param LineContentConverter $converter */
    public function __construct(LineContentConverter $converter)
    {
        $this->converter = $converter;
    }

    /** {@inheritdoc} */
    protected function findIn(array $lines, $pattern)
    {
        return array_search($pattern, $lines, true);
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
