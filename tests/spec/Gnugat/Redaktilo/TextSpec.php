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

use Gnugat\Redaktilo\Service\LineBreak;
use PhpSpec\ObjectBehavior;

class TextSpec extends ObjectBehavior
{
    private $lines;
    private $lineBreak;

    function let()
    {
        $rootPath = __DIR__.'/../../../../';
        $filename = '%s/tests/fixtures/sources/life-of-brian.txt';
        $content = file_get_contents(sprintf($filename, $rootPath));

        $lineBreak = new LineBreak();
        $this->lineBreak = $lineBreak->detect($content);
        $this->lines = explode($this->lineBreak, $content);

        $this->beConstructedWith($this->lines, $this->lineBreak);
    }

    function it_has_lines()
    {
        $newContent = array(
            'This',
            'is an EX parrot!'
        );

        $this->getLines()->shouldBe($this->lines);
        $this->setLines($newContent);
        $this->getLines()->shouldBe($newContent);
    }

    function it_has_a_current_line_number()
    {
        $this->getCurrentLineNumber()->shouldBe(0);

        $middleLine = intval(count($this->lines) / 2);

        $this->setCurrentLineNumber($middleLine);
        $this->getCurrentLineNumber()->shouldBe($middleLine);
    }

    function it_fails_when_the_line_number_is_invalid()
    {
        $exception = '\InvalidArgumentException';

        $this->shouldThrow($exception)->duringSetCurrentLineNumber('toto');
        $this->shouldThrow($exception)->duringSetCurrentLineNumber(-1);
        $this->shouldThrow($exception)->duringSetCurrentLineNumber(9);
    }

    function it_has_a_line_break()
    {
        $newLineBreak = '\r\n';

        $this->getLineBreak()->shouldBe($this->lineBreak);
        $this->setLineBreak($newLineBreak);
        $this->getLineBreak()->shouldBe($newLineBreak);
    }
}
