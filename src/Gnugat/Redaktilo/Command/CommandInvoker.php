<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Command;

use Gnugat\Redaktilo\Exception\CommandNotFoundException;

/**
 * Executes a command with the given input.
 */
class CommandInvoker
{
    /** @var Command[] */
    private $commands = [];

    /** @param Command $command */
    public function addCommand(Command $command)
    {
        $this->commands[$command->getName()] = $command;
    }

    /**
     * @param string $name
     *
     * @throws CommandNotFoundException
     */
    public function run($name, array $input)
    {
        if (!isset($this->commands[$name])) {
            throw new CommandNotFoundException($name, $this->commands);
        }
        $this->commands[$name]->execute($input);
    }
}
