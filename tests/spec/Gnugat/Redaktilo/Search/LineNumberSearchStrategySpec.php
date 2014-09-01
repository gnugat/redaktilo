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

class LineNumberSearchStrategySpec extends ObjectBehavior
{
    const FILENAME = '%s/tests/fixtures/sources/life-of-brian.txt';

    function let(Text $text)
    {
        $rootPath = __DIR__.'/../../../../..';

        $filename = sprintf(self::FILENAME, $rootPath);
        $lines = file($filename, FILE_IGNORE_NEW_LINES);

        $text->getLength()->willReturn(count($lines));
    }

    function it_is_a_search_strategy()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Search\SearchStrategy');
    }

    function it_supports_line_numbers()
    {
        $lineNumber = 42;
        $line = 'Sir Bedevere: Good. Now, why do witches burn?';
        $rawLine = $line."\n";

        $this->supports($lineNumber)->shouldBe(true);
        $this->supports($line)->shouldBe(false);
        $this->supports($rawLine)->shouldBe(false);
    }

    function it_finds_above_occurences(Text $text)
    {
        $currentLineNumber = 5;
        $existingLine = 4;
        $belowLineNumber = 1;
        $nonExistingLine = 23;

        $this->findAbove($text, $nonExistingLine, $currentLineNumber)->shouldBe(false);
        $this->findAbove($text, $existingLine, $currentLineNumber)->shouldBe($belowLineNumber);

        $text->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findAbove($text, $nonExistingLine)->shouldBe(false);
        $this->findAbove($text, $existingLine)->shouldBe($belowLineNumber);
    }

    function it_finds_below_occurences(Text $text)
    {
        $currentLineNumber = 5;
        $existingLine = 2;
        $belowLineNumber = 7;
        $nonExistingLine = 23;

        $this->findBelow($text, $nonExistingLine, $currentLineNumber)->shouldBe(false);
        $this->findBelow($text, $existingLine, $currentLineNumber)->shouldBe($belowLineNumber);

        $text->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findBelow($text, $nonExistingLine)->shouldBe(false);
        $this->findBelow($text, $existingLine)->shouldBe($belowLineNumber);
    }
}
