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

use Gnugat\Redaktilo\Converter\LineContentConverter;
use Gnugat\Redaktilo\File;
use PhpSpec\ObjectBehavior;

class SameSearchStrategySpec extends ObjectBehavior
{
    const FILENAME = '%s/tests/fixtures/sources/life-of-brian.txt';

    function let(File $file, LineContentConverter $converter)
    {
        $rootPath = __DIR__.'/../../../../..';

        $filename = sprintf(self::FILENAME, $rootPath);
        $lines = file($filename, FILE_IGNORE_NEW_LINES);

        $file->getFilename()->willReturn($filename);
        $converter->from($file)->willReturn($lines);
        $this->beConstructedWith($converter);
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

    function it_finds_above_occurences(File $file)
    {
        $aboveLine = '[A guard sniggers]';
        $aboveLineNumber = 1;
        $currentLine = '[More sniggering]';
        $currentLineNumber = 3;
        $underLine = '[Sniggering]';

        $this->findAbove($file, $underLine, $currentLineNumber)->shouldBe(false);
        $this->findAbove($file, $currentLine, $currentLineNumber)->shouldBe(false);
        $this->findAbove($file, $aboveLine, $currentLineNumber)->shouldBe($aboveLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findAbove($file, $underLine)->shouldBe(false);
        $this->findAbove($file, $currentLine)->shouldBe(false);
        $this->findAbove($file, $aboveLine)->shouldBe($aboveLineNumber);
    }

    function it_finds_under_occurences(File $file)
    {
        $aboveLine = '[A guard sniggers]';
        $currentLine = '[More sniggering]';
        $currentLineNumber = 3;
        $underLine = '[Sniggering]';
        $underLineNumber = 5;

        $this->findUnder($file, $aboveLine, $currentLineNumber)->shouldBe(false);
        $this->findUnder($file, $currentLine, $currentLineNumber)->shouldBe(false);
        $this->findUnder($file, $underLine, $currentLineNumber)->shouldBe($underLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findUnder($file, $aboveLine)->shouldBe(false);
        $this->findUnder($file, $currentLine)->shouldBe(false);
        $this->findUnder($file, $underLine)->shouldBe($underLineNumber);
    }
}
