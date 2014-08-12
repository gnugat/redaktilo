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

use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Service\LineBreak;
use PhpSpec\ObjectBehavior;

class LineBreakSpec extends ObjectBehavior
{
    function it_detects_file_line_break()
    {
        $lines = array(
            'King Arthur: One, two, five!',
            'Sir Galahad: Three sir!',
            'King Arthur: THREE!',
        );
        $fileWithoutLines = $lines[0];
        $windowsFile = implode(LineBreak::LINE_BREAK_WINDOWS, $lines);
        $otherFile = implode(LineBreak::LINE_BREAK_OTHER, $lines);

        $this->detect($fileWithoutLines)->shouldBe(PHP_EOL);
        $this->detect($windowsFile)->shouldBe(LineBreak::LINE_BREAK_WINDOWS);
        $this->detect($otherFile)->shouldBe(LineBreak::LINE_BREAK_OTHER);
    }
}
