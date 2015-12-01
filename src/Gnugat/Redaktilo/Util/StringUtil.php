<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Util;

use Gnugat\Redaktilo\Exception\DifferentLineBreaksFoundException;

/**
 * @author Wouter J <wouter@wouterj.nl>
 */
final class StringUtil
{
    const LINE_BREAK_OTHER = "\n";
    const LINE_BREAK_WINDOWS = "\r\n";

    public static function breakIntoLines($string)
    {
        return preg_split('/\R/', $string);
    }

    public static function detectLineBreak($string)
    {
        $numberLineBreakWindows = substr_count($string, self::LINE_BREAK_WINDOWS);
        $numberLineBreakOther = substr_count($string, self::LINE_BREAK_OTHER) - $numberLineBreakWindows;

        if ($numberLineBreakOther === 0 && $numberLineBreakWindows === 0) {
            return PHP_EOL;
        }

        if ($numberLineBreakOther > 0 && $numberLineBreakWindows > 0) {
            throw new DifferentLineBreaksFoundException(
                $string,
                $numberLineBreakOther,
                $numberLineBreakWindows
            );
        }

        return $numberLineBreakOther > 0 ? self::LINE_BREAK_OTHER : self::LINE_BREAK_WINDOWS;
    }
}
