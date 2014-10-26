<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Command;

use Gnugat\Redaktilo\Command\Command;
use PhpSpec\ObjectBehavior;

class CommandInvokerSpec extends ObjectBehavior
{
    function let(Command $command)
    {
        $this->addCommand($command);
    }

    function it_fails_when_the_command_is_not_supported(Command $command)
    {
        $name = 'test';
        $exception = '\Gnugat\Redaktilo\Exception\CommandNotFoundException';
        $input = array();

        $command->getName()->willReturn('some-command');

        $this->addCommand($command);
        $this->shouldThrow($exception)->duringRun($name, $input);
    }

    function it_executes_the_wanted_command(Command $command)
    {
        $name = 'another-command';
        $input = array();

        $command->getName()->willReturn('another-command');
        $command->execute($input)->shouldBeCalled();

        $this->addCommand($command);
        $this->run($name, $input);
    }
}
