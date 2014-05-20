<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\FactoryMethod;

/**
 * A convenient wrapper to keep readability when manipulating line numbers.
 *
 * @api
 */
class LineNumber
{
    /**
     * @param int $lineNumber
     *
     * @return int
     */
    public static function absolute($lineNumber)
    {
        return self::normalize($lineNumber);
    }

    /**
     * @param int $lines
     *
     * @return int
     */
    public static function down($lines)
    {
        return self::normalize($lines);
    }

    /**
     * @param int $lines
     *
     * @return int
     */
    public static function up($lines)
    {
        return self::normalize($lines);
    }

    /**
     * Line numbers should be strictly positive integers.
     *
     * @param int $number
     *
     * @return int
     */
    private static function normalize($number)
    {
        $integer = intval($number);

        return max($integer, 0);
    }
}
