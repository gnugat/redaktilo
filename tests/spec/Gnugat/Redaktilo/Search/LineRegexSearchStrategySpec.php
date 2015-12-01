<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\Text;
use PhpSpec\ObjectBehavior;

class LineRegexSearchStrategySpec extends ObjectBehavior
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

    function it_supports_lines_regex()
    {
        $regexp = '#\.{3}Dickus\?#';
        $line = 'Sir Bedevere: Good. Now, why do witches burn?';
        $rawLine = $line."\n";
        $lineNumber = 42;

        $this->supports($regexp)->shouldBe(true);
        $this->supports($line)->shouldBe(false);
        $this->supports($rawLine)->shouldBe(false);
        $this->supports($lineNumber)->shouldBe(false);
    }

    function it_finds_above_occurences(Text $text)
    {
        $aboveLineRegex = '/\[A \w+ sniggers\]/';
        $aboveLineNumber = 1;
        $immediateAboveLineRegex = '/^Pontius Pilate: \'.../';
        $immediateAboveLineNumber = 2;
        $currentLineRegex = '/More sniggering/';
        $currentLineNumber = 3;
        $belowLineRegex = '/\[Sniggering\]/';

        $this->findAbove($text, $belowLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findAbove($text, $currentLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findAbove($text, $immediateAboveLineRegex, $currentLineNumber)->shouldBe($immediateAboveLineNumber);
        $this->findAbove($text, $aboveLineRegex, $currentLineNumber)->shouldBe($aboveLineNumber);

        $text->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findAbove($text, $belowLineRegex)->shouldBe(false);
        $this->findAbove($text, $currentLineRegex)->shouldBe(false);
        $this->findAbove($text, $immediateAboveLineRegex)->shouldBe($immediateAboveLineNumber);
        $this->findAbove($text, $aboveLineRegex)->shouldBe($aboveLineNumber);
    }

    function it_finds_below_occurences(Text $text)
    {
        $aboveLineRegex = '/\[A \w+ sniggers\]/';
        $currentLineRegex = '/More sniggering/';
        $currentLineNumber = 3;
        $immediateLineBelowRegex = '/^Pontius Pilate: \'What/';
        $immediateLineBelowNumber = 4;
        $belowLineRegex = '/\[Sniggering\]/';
        $belowLineNumber = 5;

        $this->findBelow($text, $aboveLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findBelow($text, $currentLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findBelow($text, $immediateLineBelowRegex, $currentLineNumber)->shouldBe($immediateLineBelowNumber);
        $this->findBelow($text, $belowLineRegex, $currentLineNumber)->shouldBe($belowLineNumber);

        $text->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findBelow($text, $aboveLineRegex)->shouldBe(false);
        $this->findBelow($text, $currentLineRegex)->shouldBe(false);
        $this->findBelow($text, $immediateLineBelowRegex)->shouldBe($immediateLineBelowNumber);
        $this->findBelow($text, $belowLineRegex)->shouldBe($belowLineNumber);
    }

    function it_finds_relatively_to_the_first_line(Text $text)
    {
        $pattern = '/\[Sniggering\]/';
        $lineNumber = 5;
        $text->getCurrentLineNumber()->shouldNotBeCalled();

        $this->findAbove($text, $pattern, 0)->shouldBe(false);
        $this->findBelow($text, $pattern, 0)->shouldBe($lineNumber);
    }
}
