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

use Gnugat\Redaktilo\Text;

/**
 * A lookup strategy supporting a specific kind of pattern.
 *
 * @api
 */
interface SearchStrategy
{
    /**
     * Looks for the given pattern from the given line number ($location) to the
     * top of the Text.
     * If no line number is given, the current line number of the text is used.
     * If the pattern doesn't match anything, returns false.
     *
     * @param Text  $text
     * @param mixed $pattern
     * @param int   $location
     *
     * @return mixed
     */
    public function findAbove(Text $text, $pattern, $location = null);

    /**
     * Looks for the given pattern from the given line number ($location) to the
     * bottom of the Text.
     * If no line number is given, the current line number of the text is used.
     * If the pattern doesn't match anything, returns false.
     *
     * @param Text  $text
     * @param mixed $pattern
     * @param int   $location
     *
     * @return mixed
     */
    public function findBelow(Text $text, $pattern, $location = null);

    /**
     * @param mixed $pattern
     *
     * @return bool
     */
    public function supports($pattern);
}
