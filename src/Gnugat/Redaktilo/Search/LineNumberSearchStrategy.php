<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\Text;

/**
 * Moves x lines above or below the current one.
 *
 * @deprecated 1.7 Use Text#setCurrentLineNumber instead
 */
class LineNumberSearchStrategy implements SearchStrategy
{
    /** {@inheritdoc} */
    public function findAbove(Text $text, $pattern, $location = null)
    {
        trigger_error(__CLASS__.' has been replaced by Text#setCurrentLineNumber', \E_USER_DEPRECATED);
        $foundLineNumber = (null !== $location ? $location : $text->getCurrentLineNumber()) - $pattern;
        if (0 > $foundLineNumber || $foundLineNumber >= $text->getLength()) {
            return false;
        }

        return $foundLineNumber;
    }

    /** {@inheritdoc} */
    public function findBelow(Text $text, $pattern, $location = null)
    {
        trigger_error(__CLASS__.' has been replaced by Text#setCurrentLineNumber', \E_USER_DEPRECATED);
        $foundLineNumber = (null !== $location ? $location : $text->getCurrentLineNumber()) + $pattern;
        if (0 > $foundLineNumber || $foundLineNumber >= $text->getLength()) {
            return false;
        }

        return $foundLineNumber;
    }

    /** {@inheritdoc} */
    public function supports($pattern)
    {
        trigger_error(__CLASS__.' has been replaced by Text#setCurrentLineNumber', \E_USER_DEPRECATED);

        return is_int($pattern) && $pattern >= 0;
    }
}
