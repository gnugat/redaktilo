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

/**
 * Thrown if the string given to LineBreak service contains different line breaks.
 *
 * @deprecated since 1.4, use the class from the Exception namespace instead
 */
abstract class DifferentLineBreaksFoundException extends \Exception
{
    /** @return string */
    abstract public function getString();

    /** @return int */
    abstract public function getNumberLineBreakOther();

    /** @return int */
    abstract public function getNumberLineBreakWindows();
}
