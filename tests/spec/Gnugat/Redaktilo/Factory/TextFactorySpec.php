<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Factory;

use PhpSpec\ObjectBehavior;
use Gnugat\Redaktilo\Service\LineBreak;

class TextFactorySpec extends ObjectBehavior
{
    private $lineBreak;

    function let()
    {
        $this->lineBreak = new LineBreak();

        $this->beConstructedWith($this->lineBreak);
    }

    function it_creates_a_text_from_string()
    {
        $filename = __DIR__.'/../../../../../tests/fixtures/sources/life-of-brian.txt';
        $content = file_get_contents($filename);

        $lineBreak = $this->lineBreak->detect($content);
        $lines = explode($lineBreak, $content);

        $text = $this->make($content);

        $text->shouldBeAnInstanceOf('Gnugat\Redaktilo\Text');
        $text->getLines()->shouldBe($lines);
        $text->getLineBreak()->shouldBe($lineBreak);
    }
}
