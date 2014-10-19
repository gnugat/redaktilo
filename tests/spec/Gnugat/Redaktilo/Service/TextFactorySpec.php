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
use PhpSpec\ObjectBehavior;
use Gnugat\Redaktilo\Service\LineBreak;

class TextFactorySpec extends ObjectBehavior
{
    function let(LineBreak $lineBreak)
    {
        $this->beConstructedWith($lineBreak);
    }

    function it_creates_a_text_from_string(LineBreak $lineBreak)
    {
        $filename = __DIR__.'/../../../../../tests/fixtures/sources/life-of-brian.txt';
        $content = file_get_contents($filename);

        $lineBreak->detect($content)->willReturn(LineBreak::LINE_BREAK_OTHER);
        $lines = explode(LineBreak::LINE_BREAK_OTHER, $content);

        $text = $this->make($content);

        $text->shouldBeAnInstanceOf('Gnugat\Redaktilo\Text');
        $text->getLines()->shouldBe($lines);
        $text->getLineBreak()->shouldBe(LineBreak::LINE_BREAK_OTHER);
    }

    function it_creates_a_text_from_string_with_different_line_breaks(LineBreak $lineBreak)
    {
        $lines = array(
            'E.T. phone home.',
            'I\'ll be back.',
            'Are you talkin\' to me?',
            'Houston, We Have a Problem.'
        );

        $content =
            $lines[0].LineBreak::LINE_BREAK_WINDOWS
            .$lines[1].LineBreak::LINE_BREAK_OTHER
            .$lines[2].LineBreak::LINE_BREAK_WINDOWS
            .$lines[3]
        ;

        $exception = new DifferentLineBreaksFoundException(
            $content,
            1,
            2
        );

        $lineBreak->detect($content)->willThrow($exception);

        $text = $this->make($content);

        $text->shouldBeAnInstanceOf('Gnugat\Redaktilo\Text');
        $text->getLines()->shouldBe($lines);
        $text->getLineBreak()->shouldBe(LineBreak::LINE_BREAK_WINDOWS);
    }
}
