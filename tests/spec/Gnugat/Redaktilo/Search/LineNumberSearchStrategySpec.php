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

class LineNumberSearchStrategySpec extends ObjectBehavior
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

    function it_supports_line_numbers()
    {
        $lineNumber = 42;
        $line = 'Sir Bedevere: Good. Now, why do witches burn?';
        $rawLine = $line."\n";

        $this->supports($lineNumber)->shouldBe(true);
        $this->supports($line)->shouldBe(false);
        $this->supports($rawLine)->shouldBe(false);
    }

    function it_checks_line_presence(File $file)
    {
        $existingLine = 5;
        $nonExistingLine = 1337;

        $this->has($file, $existingLine)->shouldBe(true);
        $this->has($file, $nonExistingLine)->shouldBe(false);
    }

    function it_finds_previous_occurences(File $file)
    {
        $currentLineNumber = 5;
        $existingLine = 4;
        $nextLineNumber = 1;
        $nonExistingLine = 23;

        $this->findPrevious($file, $nonExistingLine, $currentLineNumber)->shouldBe(false);
        $this->findPrevious($file, $existingLine, $currentLineNumber)->shouldBe($nextLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findPrevious($file, $nonExistingLine)->shouldBe(false);
        $this->findPrevious($file, $existingLine)->shouldBe($nextLineNumber);
    }

    function it_finds_next_occurences(File $file)
    {
        $currentLineNumber = 5;
        $existingLine = 2;
        $nextLineNumber = 7;
        $nonExistingLine = 23;

        $this->findNext($file, $nonExistingLine, $currentLineNumber)->shouldBe(false);
        $this->findNext($file, $existingLine, $currentLineNumber)->shouldBe($nextLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findNext($file, $nonExistingLine)->shouldBe(false);
        $this->findNext($file, $existingLine)->shouldBe($nextLineNumber);
    }
}
