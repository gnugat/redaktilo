<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo;

/**
 * Thrown if the given line number isn't a positive integer strictly inferior to
 * the total number of lines in text.
 *
 * @api
 *
 * @deprecated since 1.4, use the class from the Exception namespace instead
 */
abstract class InvalidLineNumberException extends \InvalidArgumentException
{
    /** @return mixed */
    abstract public function getLineNumber();

    /** @return Text */
    abstract public function getText();
}
