<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Command;

/**
 * Thrown if the name given to CommandInvoker isn't in its collection.
 *
 * @api
 *
 * @deprecated since 1.4, use the class from the Exception namespace instead
 */
abstract class CommandNotFoundException extends \Exception
{
    /** @return string */
    abstract public function getName();

    /** @return array */
    abstract public function getCommands();
}
