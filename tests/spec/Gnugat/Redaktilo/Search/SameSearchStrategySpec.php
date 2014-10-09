<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\Text;
use PhpSpec\ObjectBehavior;

class SameSearchStrategySpec extends ObjectBehavior
{
    const FILENAME = '%s/tests/fixtures/sources/life-of-brian.txt';

    function let(Text $text)
    {
        $rootPath = __DIR__.'/../../../../..';

        $filename = sprintf(self::FILENAME, $rootPath);
        $lines = file($filename, FILE_IGNORE_NEW_LINES);

        $text->getLines()->willReturn($lines);
    }

    function it_is_a_search_strategy()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Search\SearchStrategy');
    }

    function it_supports_lines()
    {
        $line = 'Sir Bedevere: Good. Now, why do witches burn?';
        $rawLine = $line."\n";
        $lineNumber = 42;

        $this->supports($line)->shouldBe(true);
        $this->supports($rawLine)->shouldBe(false);
        $this->supports($lineNumber)->shouldBe(false);
    }

    function it_finds_above_occurences(Text $text)
    {
        $aboveLine = '[A guard sniggers]';
        $aboveLineNumber = 1;
        $immediateAboveLine = 'Pontius Pilate: \'...Dickus?\'';
        $immediateAboveLineNumber = 2;
        $currentLine = '[More sniggering]';
        $currentLineNumber = 3;
        $belowLine = '[Sniggering]';

        $this->findAbove($text, $belowLine, $currentLineNumber)->shouldBe(false);
        $this->findAbove($text, $currentLine, $currentLineNumber)->shouldBe(false);
        $this->findAbove($text, $immediateAboveLine, $currentLineNumber)->shouldBe($immediateAboveLineNumber);
        $this->findAbove($text, $aboveLine, $currentLineNumber)->shouldBe($aboveLineNumber);

        $text->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findAbove($text, $belowLine)->shouldBe(false);
        $this->findAbove($text, $currentLine)->shouldBe(false);
        $this->findAbove($text, $immediateAboveLine)->shouldBe($immediateAboveLineNumber);
        $this->findAbove($text, $aboveLine)->shouldBe($aboveLineNumber);
    }

    function it_finds_below_occurences(Text $text)
    {
        $aboveLine = '[A guard sniggers]';
        $currentLine = '[More sniggering]';
        $currentLineNumber = 3;
        $belowLine = '[Sniggering]';
        $belowLineNumber = 5;

        $this->findBelow($text, $aboveLine, $currentLineNumber)->shouldBe(false);
        $this->findBelow($text, $currentLine, $currentLineNumber)->shouldBe(false);
        $this->findBelow($text, $belowLine, $currentLineNumber)->shouldBe($belowLineNumber);

        $text->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findBelow($text, $aboveLine)->shouldBe(false);
        $this->findBelow($text, $currentLine)->shouldBe(false);
        $this->findBelow($text, $belowLine)->shouldBe($belowLineNumber);
    }
}
