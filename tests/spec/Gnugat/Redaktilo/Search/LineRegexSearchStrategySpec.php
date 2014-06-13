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

class LineRegexSearchStrategySpec extends ObjectBehavior
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

    function it_checks_line_presence(File $file)
    {
        $existingLineRegex = '#\.{3}Dickus#';
        $nonExistingLineRegex = '/ThereIsNoSuchALine/';

        $this->has($file, $existingLineRegex)->shouldBe(true);
        $this->has($file, $nonExistingLineRegex)->shouldBe(false);
    }

    function it_finds_previous_occurences(File $file)
    {
        $previousLineRegex = '/\[A \w+ sniggers\]/';
        $previousLineNumber = 1;
        $currentLineRegex = '/More sniggering/';
        $currentLineNumber = 3;
        $nextLineRegex = '/\[Sniggering\]/';

        $this->findPrevious($file, $nextLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findPrevious($file, $currentLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findPrevious($file, $previousLineRegex, $currentLineNumber)->shouldBe($previousLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findPrevious($file, $nextLineRegex)->shouldBe(false);
        $this->findPrevious($file, $currentLineRegex)->shouldBe(false);
        $this->findPrevious($file, $previousLineRegex)->shouldBe($previousLineNumber);
    }

    function it_finds_next_occurences(File $file)
    {
        $previousLineRegex = '/\[A \w+ sniggers\]/';
        $currentLineRegex = '/More sniggering/';
        $currentLineNumber = 3;
        $nextLineRegex = '/\[Sniggering\]/';
        $nextLineNumber = 5;

        $this->findNext($file, $previousLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findNext($file, $currentLineRegex, $currentLineNumber)->shouldBe(false);
        $this->findNext($file, $nextLineRegex, $currentLineNumber)->shouldBe($nextLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findNext($file, $previousLineRegex)->shouldBe(false);
        $this->findNext($file, $currentLineRegex)->shouldBe(false);
        $this->findNext($file, $nextLineRegex)->shouldBe($nextLineNumber);
    }
}
