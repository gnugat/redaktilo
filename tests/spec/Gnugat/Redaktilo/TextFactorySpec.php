<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo;

use PhpSpec\ObjectBehavior;
use Gnugat\Redaktilo\Converter\LineContentConverter;

class TextFactorySpec extends ObjectBehavior
{
    private $lineContentConverter;

    function let()
    {
        $this->lineContentConverter = new LineContentConverter();

        $this->beConstructedWith($this->lineContentConverter);
    }

    function it_creates_a_text_from_string()
    {
        $filename = __DIR__.'/../../../../tests/fixtures/sources/life-of-brian.txt';
        $content = file_get_contents($filename);

        $lineBreak = $this->lineContentConverter->detectLineBreak($content);
        $lines = explode($lineBreak, $content);

        $text = $this->make($content);

        $text->shouldBeAnInstanceOf('Gnugat\Redaktilo\Text');
        $text->getLines()->shouldBe($lines);
        $text->getLineBreak()->shouldBe($lineBreak);
    }
}
