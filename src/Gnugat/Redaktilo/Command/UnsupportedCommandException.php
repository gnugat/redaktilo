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
 * Thrown if the name given to CommandInvoker isn't supported by any of its command.
 *
 * @api
 */
class UnsupportedCommandException extends \Exception
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $message = sprintf('No command was found with name "%s"', $name);

        parent::__construct($message);
    }
}
