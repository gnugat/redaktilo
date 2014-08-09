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

    function it_finds_above_occurences(File $file)
    {
        $currentLineNumber = 5;
        $existingLine = 4;
        $underLineNumber = 1;
        $nonExistingLine = 23;

        $this->findAbove($file, $nonExistingLine, $currentLineNumber)->shouldBe(false);
        $this->findAbove($file, $existingLine, $currentLineNumber)->shouldBe($underLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findAbove($file, $nonExistingLine)->shouldBe(false);
        $this->findAbove($file, $existingLine)->shouldBe($underLineNumber);
    }

    function it_finds_under_occurences(File $file)
    {
        $currentLineNumber = 5;
        $existingLine = 2;
        $underLineNumber = 7;
        $nonExistingLine = 23;

        $this->findUnder($file, $nonExistingLine, $currentLineNumber)->shouldBe(false);
        $this->findUnder($file, $existingLine, $currentLineNumber)->shouldBe($underLineNumber);

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);

        $this->findUnder($file, $nonExistingLine)->shouldBe(false);
        $this->findUnder($file, $existingLine)->shouldBe($underLineNumber);
    }
}
