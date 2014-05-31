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
use Gnugat\Redaktilo\FactoryMethod\LineNumber;
use Gnugat\Redaktilo\File;
use PhpSpec\ObjectBehavior;

class LineNumberSearchStrategySpec extends ObjectBehavior
{
    const FILENAME = '%s/tests/fixtures/sources/life-of-brian.txt';

    function let(File $file, LineContentConverter $converter)
    {
        $rootPath = __DIR__.'/../../../../../';

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
        $lineNumber = LineNumber::absolute(42);
        $line = 'Sir Bedevere: Good. Now, why do witches burn?';
        $rawLine = $line."\n";

        $this->supports($lineNumber)->shouldBe(true);
        $this->supports($line)->shouldBe(false);
        $this->supports($rawLine)->shouldBe(false);
    }

    function it_checks_line_presence(File $file)
    {
        $existingLine = LineNumber::absolute(5);
        $nonExistingLine = LineNumber::absolute(1337);

        $this->has($file, $existingLine)->shouldBe(true);
        $this->has($file, $nonExistingLine)->shouldBe(false);
    }

    function it_finds_next_occurences(File $file)
    {
        $currentLineNumber = LineNumber::absolute(5);
        $existingLine = LineNumber::down(2);
        $nextLineNumber = LineNumber::absolute(7);
        $nonExistingLine = LineNumber::down(23);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';

        $this->shouldThrow($exception)->duringFindNext($file, $nonExistingLine);
        $this->findNext($file, $existingLine)->shouldBe($nextLineNumber);
    }

    function it_finds_previous_occurences(File $file)
    {
        $currentLineNumber = LineNumber::absolute(5);
        $existingLine = LineNumber::up(4);
        $nextLineNumber = LineNumber::absolute(1);
        $nonExistingLine = LineNumber::up(23);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';

        $this->shouldThrow($exception)->duringFindPrevious($file, $nonExistingLine);
        $this->findPrevious($file, $existingLine)->shouldBe($nextLineNumber);
    }
}
