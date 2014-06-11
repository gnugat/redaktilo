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

class CommandInvoker
{
    /** @var array */
    protected $commands = array();

    /**
     * @param Command $command
     */
    public function addCommand(Command $command)
    {
        $this->commands[$command->getName()] = $command;
    }

    /**
     * @param string $name
     * @param array $input
     *
     * @throws \Gnugat\Redaktilo\Command\UnsupportedCommandException
     */
    public function run($name, array $input)
    {
        $command = $this->resolve($name, $input);
        if (null === $command) {
            throw new UnsupportedCommandException($name);
        }

        $command->execute($input);
    }

    /**
     * @param string $name
     * @param array $input
     *
     * @return Command|null
     *
     */
    protected function resolve($name, array $input)
    {
        if (isset($this->commands[$name])) {
            return $this->commands[$name];
        }
        return null;
    }
} 
