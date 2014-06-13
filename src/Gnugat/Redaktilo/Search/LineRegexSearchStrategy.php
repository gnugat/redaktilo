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

/**
 * Tries to match the given regex against each lines.
 */
class LineRegexSearchStrategy extends LineSearchStrategy
{
    /** @param LineContentConverter $converter */
    public function __construct(LineContentConverter $converter)
    {
        $this->converter = $converter;
    }

    /** {@inheritdoc} */
    protected function findIn(array $lines, $pattern)
    {
        $found = preg_grep($pattern, $lines);
        if (empty($found)) {
            return false;
        }

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
