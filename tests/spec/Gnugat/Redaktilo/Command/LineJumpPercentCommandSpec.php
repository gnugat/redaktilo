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

class LineJumpPercentCommandSpec extends ObjectBehavior
{
    const TOP = 0;
    const TOP_PERCENT = 0;

    const MIDDLE = 21;
    const MIDDLE_PERCENT = 50;

    const BOTTOM = 41;
    const BOTTOM_PERCENT = 100;

    const LENGTH = 42;

    function it_is_a_command()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Command\Command');
    }

    function it_jumps_to_the_given_percentage(Text $text)
    {
        $text->getLength()->willReturn(self::LENGTH);
        $input = array('text' => $text);

        $input['number'] = self::TOP_PERCENT;
        $text->setCurrentLineNumber(self::TOP)->shouldBeCalled();
        $this->execute($input);

        $input['number'] = self::MIDDLE_PERCENT;
        $text->setCurrentLineNumber(self::MIDDLE)->shouldBeCalled();
        $this->execute($input);

        $input['number'] = self::BOTTOM_PERCENT;
        $text->setCurrentLineNumber(self::BOTTOM)->shouldBeCalled();
        $this->execute($input);
    }
}
