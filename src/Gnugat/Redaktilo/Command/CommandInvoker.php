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
 * Executes a command with the given input.
 */
class CommandInvoker
{
    /** @var array of Command */
    private $commands = array();

    /** @param Command $command */
    public function addCommand(Command $command)
    {
        $this->commands[$command->getName()] = $command;
    }

    /**
     * @param string $name
     * @param array  $input
     *
     * @throws CommandNotFoundException
     */
    public function run($name, array $input)
    {
        if (!isset($this->commands[$name])) {
            throw new CommandNotFoundException($name);
        }
        $this->commands[$name]->execute($input);
    }
}
