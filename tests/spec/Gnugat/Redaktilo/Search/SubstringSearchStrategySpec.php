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

class SubstringSearchStrategySpec extends ObjectBehavior
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
        $substring = 'why do witches burn?';
        $substringWithLineBreak = "She turned me into a newt!\nA newt?\nI got better...";
        $lineNumber = 42;

        $this->supports($substring)->shouldBe(true);
        $this->supports($substringWithLineBreak)->shouldBe(false);
        $this->supports($lineNumber)->shouldBe(false);
    }

    function it_checks_line_presence(File $file)
    {
        $existingSubstring = '...Dickus?';
        $nonExistingSubstring = 'Cornwall?';

        $this->has($file, $existingSubstring)->shouldBe(true);
        $this->has($file, $nonExistingSubstring)->shouldBe(false);
    }

    function it_finds_next_occurences(File $file)
    {
        $previousSubstring = 'sniggers';
        $currentSubstring = 'More';
        $currentLineNumber = 3;
        $nextSubstring = 'Sniggering';
        $nextLineNumber = 5;

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findNext($file, $previousSubstring)->shouldBe(false);
        $this->findNext($file, $currentSubstring)->shouldBe(false);
        $this->findNext($file, $nextSubstring)->shouldBe($nextLineNumber);
    }

    function it_finds_previous_occurences(File $file)
    {
        $previousSubstring = 'sniggers';
        $previousLineNumber = 1;
        $currentSubstring = 'More';
        $currentLineNumber = 3;
        $nextSubstring = 'Sniggering';

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findPrevious($file, $nextSubstring)->shouldBe(false);
        $this->findPrevious($file, $currentSubstring)->shouldBe(false);
        $this->findPrevious($file, $previousSubstring)->shouldBe($previousLineNumber);
    }
}
