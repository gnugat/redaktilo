<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Exception;

/**
 * Thrown if the name given to CommandInvoker isn't in its collection.
 *
 * @api
 */
class CommandNotFoundException extends \LogicException implements Exception
{
    /** @var string */
    private $name;

    /** @var \Gnugat\Redaktilo\Command\Command[] */
    private $commands;

    /**
     * @param string                              $name
     * @param \Gnugat\Redaktilo\Command\Command[] $commands
     */
    public function __construct($name, array $commands)
    {
        $this->name = $name;
        $this->commands = $commands;

        $message = sprintf('The command "%s" was not found in CommandInvoker', $name);

        parent::__construct($message);
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    /** @return \Gnugat\Redaktilo\Command\Command[] */
    public function getCommands()
    {
        return $this->commands;
    }
}
