<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Search\FactoryMethod;

/**
 * A convenient wrapper to keep readability when searching line numbers.
 *
 * @api
 */
class LineNumber
{
    /**
     * To use with:
     *
     * + $lineNumberSearchStrategy->has()
     * + $editor->has()
     *
     * @param int $lineNumber
     *
     * @return int
     */
    public static function absolute($lineNumber)
    {
        return self::normalize($lineNumber);
    }

    /**
     * To use with:
     *
     * + $lineNumberSearchStrategy->findNext()
     * + $editor->jumpDownTo()
     *
     * @param int $lines
     *
     * @return int
     */
    public static function down($lines)
    {
        return self::normalize($lines);
    }

    /**
     * To use with:
     *
     * + $lineNumberSearchStrategy->findPrevious()
     * + $editor->jumpUpTo()
     *
     * @param int $lines
     *
     * @return int
     */
    public static function up($lines)
    {
        return self::normalize($lines);
    }

    /**
     * Line numbers should be strictly posotive integers.
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
