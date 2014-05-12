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

class LineSearchStrategySpec extends ObjectBehavior
{
    const FILENAME = '%s/tests/fixtures/sources/life-of-brian.txt';

    function let(File $file)
    {
        $rootPath = __DIR__.'/../../../../../';

        $filename = sprintf(self::FILENAME, $rootPath);
        $lines = file($filename, FILE_IGNORE_NEW_LINES);

        $file->getFilename()->willReturn($filename);
        $file->readlines()->willReturn($lines);
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

        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';

        $this->shouldThrow($exception)->duringFindNext($file, $previousLine);
        $this->shouldThrow($exception)->duringFindNext($file, $currentLine);
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

        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';

        $this->shouldThrow($exception)->duringFindPrevious($file, $nextLine);
        $this->shouldThrow($exception)->duringFindPrevious($file, $currentLine);
        $this->findPrevious($file, $previousLine)->shouldBe($previousLineNumber);
    }
}
