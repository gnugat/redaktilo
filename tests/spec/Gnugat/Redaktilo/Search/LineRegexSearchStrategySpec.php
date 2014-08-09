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

use Gnugat\Redaktilo\File;
use PhpSpec\ObjectBehavior;

class LineRegexSearchStrategySpec extends ObjectBehavior
{
    const FILENAME = '%s/tests/fixtures/sources/life-of-brian.txt';

    function let(File $file)
    {
        $rootPath = __DIR__.'/../../../../..';

        $filename = sprintf(self::FILENAME, $rootPath);
        $lines = file($filename, FILE_IGNORE_NEW_LINES);

        $file->getFilename()->willReturn($filename);
        $file->getLines()->willReturn($lines);
        $this->beConstructedWith();
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

    function it_finds_above_occurences(File $file)
    {
        $aboveLineRegex = '/\[A \w+ sniggers\]/';
        $aboveLineNumber = 1;
        $currentLineRegex = '/More sniggering/';
        $currentLineNumber = 3;
        $underLineRegex = '/\[Sniggering\]/';

        $this->findAbove($file, $underLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findAbove($file, $currentLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findAbove($file, $aboveLineRegex, $currentLineNumber)->shouldBe($aboveLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findAbove($file, $underLineRegex)->shouldBe(false);
        $this->findAbove($file, $currentLineRegex)->shouldBe(false);
        $this->findAbove($file, $aboveLineRegex)->shouldBe($aboveLineNumber);
    }

    function it_finds_under_occurences(File $file)
    {
        $aboveLineRegex = '/\[A \w+ sniggers\]/';
        $currentLineRegex = '/More sniggering/';
        $currentLineNumber = 3;
        $underLineRegex = '/\[Sniggering\]/';
        $underLineNumber = 5;

        $this->findUnder($file, $aboveLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findUnder($file, $currentLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findUnder($file, $underLineRegex, $currentLineNumber)->shouldBe($underLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findUnder($file, $aboveLineRegex)->shouldBe(false);
        $this->findUnder($file, $currentLineRegex)->shouldBe(false);
        $this->findUnder($file, $underLineRegex)->shouldBe($underLineNumber);
    }
}
