<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Converter;

/**
 * Detects the line break of the given string.
 *
 * PHP_EOL cannot be used to guess the line break of any text: a windows
 * user (`\r\n`) can receive a text created on another OS (`\n`).
 *
 * If the given string hasn't any lines, use PHP_EOL.
 *
 * @api
 */
class LineContentConverter
{
    const LINE_BREAK_OTHER = "\n";
    const LINE_BREAK_WINDOWS = "\r\n";

    /**
     * @param string $string
     *
     * @return string
     *
     * @api
     */
    public function detectLineBreak($string)
    {
        if (false === strpos($string, self::LINE_BREAK_OTHER)) {
            return PHP_EOL;
        }
        if (false !== strpos($string, self::LINE_BREAK_WINDOWS)) {
            return self::LINE_BREAK_WINDOWS;
        }

        return self::LINE_BREAK_OTHER;
    }
}
