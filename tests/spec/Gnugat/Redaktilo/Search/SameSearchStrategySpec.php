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
        $this->beConstructedWith();
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
        $currentLine = '[More sniggering]';
        $currentLineNumber = 3;
        $underLine = '[Sniggering]';

        $this->findAbove($text, $underLine, $currentLineNumber)->shouldBe(false);
        $this->findAbove($text, $currentLine, $currentLineNumber)->shouldBe(false);
        $this->findAbove($text, $aboveLine, $currentLineNumber)->shouldBe($aboveLineNumber);

        $text->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findAbove($text, $underLine)->shouldBe(false);
        $this->findAbove($text, $currentLine)->shouldBe(false);
        $this->findAbove($text, $aboveLine)->shouldBe($aboveLineNumber);
    }

    function it_finds_under_occurences(Text $text)
    {
        $aboveLine = '[A guard sniggers]';
        $currentLine = '[More sniggering]';
        $currentLineNumber = 3;
        $underLine = '[Sniggering]';
        $underLineNumber = 5;

        $this->findUnder($text, $aboveLine, $currentLineNumber)->shouldBe(false);
        $this->findUnder($text, $currentLine, $currentLineNumber)->shouldBe(false);
        $this->findUnder($text, $underLine, $currentLineNumber)->shouldBe($underLineNumber);

        $text->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findUnder($text, $aboveLine)->shouldBe(false);
        $this->findUnder($text, $currentLine)->shouldBe(false);
        $this->findUnder($text, $underLine)->shouldBe($underLineNumber);
    }
}
