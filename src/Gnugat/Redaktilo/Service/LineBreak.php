<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Service;

use Gnugat\Redaktilo\Exception\DifferentLineBreaksFoundException;

/**
 * Detects the line break of the given string.
 *
 * PHP_EOL cannot be used to guess the line break of any text: a windows
 * user (`\r\n`) can receive a text created on another OS (`\n`).
 *
 * If the given string hasn't any lines, use PHP_EOL.
 */
class LineBreak
{
    const LINE_BREAK_OTHER = "\n";
    const LINE_BREAK_WINDOWS = "\r\n";

    /**
     * @param string $string
     *
     * @return string
     *
     * @throws DifferentLineBreaksFoundException if different kind of line breaks are found in the string
     */
    public function detect($string)
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
