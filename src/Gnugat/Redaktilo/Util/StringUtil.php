<?php

namespace Gnugat\Redaktilo\Util;

/**
 * @author Wouter J <wouter@wouterj.nl>
 */
class StringUtil
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
