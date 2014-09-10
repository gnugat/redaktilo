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

use Gnugat\Redaktilo\Text;
use PhpSpec\ObjectBehavior;

class LineJumpBelowCommandSpec extends ObjectBehavior
{
    function it_is_a_command()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Command\Command');
    }

    function it_jumps_to_the_line_below(Text $text)
    {
        $currentLineNumber = 5;
        $number = 3;
        $expectedLineNumber = 8;

        $input = array(
            'text' => $text,
            'number' => $number,
        );
        $text->getCurrentLineNumber()->willReturn($currentLineNumber);
        $text->setCurrentLineNumber($expectedLineNumber)->shouldBeCalled();

        $this->execute($input);
    }
}
