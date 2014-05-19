<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Converter;

use Gnugat\Redaktilo\Converter\LineContentConverter;
use Gnugat\Redaktilo\File;
use PhpSpec\ObjectBehavior;

class LineContentConverterSpec extends ObjectBehavior
{
    function it_is_a_content_converter()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Converter\ContentConverter');
    }

    function it_converts_file_content_into_lines(File $file)
    {
        $lines = array(
            'Egg',
            'Bacon',
            'Sausage',
            'And spam',
        );
        $content = implode("\n", $lines);

        $file->read()->willReturn($content);
        $this->from($file)->shouldBe($lines);
    }

    function it_converts_back_lines_into_file_content(File $file)
    {
        $lines = array(
            'Spam',
            'Bacon',
            'Sausage',
            'And spam',
        );
        $content = implode("\n", $lines);

        $file->read()->willReturn($content);
        $file->write($content)->shouldBeCalled();
        $this->back($file, $lines);
    }

    function it_detects_file_line_break()
    {
        $lines = array(
            'King Arthur: One, two, five!',
            'Sir Galahad: Three sir!',
            'King Arthur: THREE!',
        );
        $fileWithoutLines = $lines[0];
        $windowsFile = implode(LineContentConverter::LINE_BREAK_WINDOWS, $lines);
        $otherFile = implode(LineContentConverter::LINE_BREAK_OTHER, $lines);

        $this->detectLineBreak($fileWithoutLines)->shouldBe(PHP_EOL);
        $this->detectLineBreak($windowsFile)->shouldBe(LineContentConverter::LINE_BREAK_WINDOWS);
        $this->detectLineBreak($otherFile)->shouldBe(LineContentConverter::LINE_BREAK_OTHER);
    }
}
