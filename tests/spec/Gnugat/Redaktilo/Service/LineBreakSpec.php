<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Service;

use Gnugat\Redaktilo\Service\DifferentLineBreaksFoundException;
use Gnugat\Redaktilo\Service\LineBreak;
use PhpSpec\ObjectBehavior;

class LineBreakSpec extends ObjectBehavior
{
    function it_detects_line_break()
    {
        $lines = array(
            'King Arthur: One, two, five!',
            'Sir Galahad: Three sir!',
            'King Arthur: THREE!',
        );
        $textWithoutLines = $lines[0];
        $windowsText = implode(LineBreak::LINE_BREAK_WINDOWS, $lines);
        $otherText = implode(LineBreak::LINE_BREAK_OTHER, $lines);

        $this->detect($textWithoutLines)->shouldBe(PHP_EOL);
        $this->detect($windowsText)->shouldBe(LineBreak::LINE_BREAK_WINDOWS);
        $this->detect($otherText)->shouldBe(LineBreak::LINE_BREAK_OTHER);
    }

    function it_fails_with_different_line_break()
    {
        $text =
            'King Arthur: One, two, five!'.LineBreak::LINE_BREAK_OTHER
            .'Sir Galahad: Three sir!'.LineBreak::LINE_BREAK_WINDOWS
            .'King Arthur: THREE!'.LineBreak::LINE_BREAK_OTHER
        ;

        $exception = new DifferentLineBreaksFoundException(
            $text,
            2,
            1
        );

        $this->shouldThrow($exception)->duringDetect($text);
    }
}
