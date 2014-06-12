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

    function it_checks_line_presence(File $file)
    {
        $existingLine = '[Sniggering]';
        $nonExistingLine = "Isn't there a Saint Aaaaarrrrrrggghhh's in Cornwall?";

        $this->has($file, $existingLine)->shouldBe(true);
        $this->has($file, $nonExistingLine)->shouldBe(false);
    }

    function it_finds_next_occurences(File $file)
    {
        $previousLine = '[A guard sniggers]';
        $currentLine = '[More sniggering]';
        $currentLineNumber = 3;
        $nextLine = '[Sniggering]';
        $nextLineNumber = 5;

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findNext($file, $previousLine)->shouldBe(false);
        $this->findNext($file, $currentLine)->shouldBe(false);
        $this->findNext($file, $nextLine)->shouldBe($nextLineNumber);
    }

    function it_finds_previous_occurences(File $file)
    {
        $previousLine = '[A guard sniggers]';
        $previousLineNumber = 1;
        $currentLine = '[More sniggering]';
        $currentLineNumber = 3;
        $nextLine = '[Sniggering]';

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findPrevious($file, $nextLine)->shouldBe(false);
        $this->findPrevious($file, $currentLine)->shouldBe(false);
        $this->findPrevious($file, $previousLine)->shouldBe($previousLineNumber);
    }
}
