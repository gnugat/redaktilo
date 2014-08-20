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
        $currentLineRegex = '/More sniggering/';
        $currentLineNumber = 3;
        $belowLineRegex = '/\[Sniggering\]/';

        $this->findAbove($text, $belowLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findAbove($text, $currentLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findAbove($text, $aboveLineRegex, $currentLineNumber)->shouldBe($aboveLineNumber);

        $text->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findAbove($text, $belowLineRegex)->shouldBe(false);
        $this->findAbove($text, $currentLineRegex)->shouldBe(false);
        $this->findAbove($text, $aboveLineRegex)->shouldBe($aboveLineNumber);
    }

    function it_finds_below_occurences(Text $text)
    {
        $aboveLineRegex = '/\[A \w+ sniggers\]/';
        $currentLineRegex = '/More sniggering/';
        $currentLineNumber = 3;
        $belowLineRegex = '/\[Sniggering\]/';
        $belowLineNumber = 5;

        $this->findBelow($text, $aboveLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findBelow($text, $currentLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findBelow($text, $belowLineRegex, $currentLineNumber)->shouldBe($belowLineNumber);

        $text->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findBelow($text, $aboveLineRegex)->shouldBe(false);
        $this->findBelow($text, $currentLineRegex)->shouldBe(false);
        $this->findBelow($text, $belowLineRegex)->shouldBe($belowLineNumber);
    }
}
