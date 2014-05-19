<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Replace;

use Gnugat\Redaktilo\Converter\LineContentConverter;
use Gnugat\Redaktilo\File;
use PhpSpec\ObjectBehavior;

class LineReplaceStrategySpec extends ObjectBehavior
{
    const ORIGINAL_FILENAME = '%s/tests/fixtures/sources/life-of-brian.txt';
    const EXPECTED_FILENAME = '%s/tests/fixtures/expectations/life-of-brian-%s.txt';

    private $rootPath;
    private $lines;

    function let(File $file, LineContentConverter $converter)
    {
        $this->rootPath = __DIR__.'/../../../../../';

        $filename = sprintf(self::ORIGINAL_FILENAME, $this->rootPath);
        $this->lines = file($filename, FILE_IGNORE_NEW_LINES);

        $converter->from($file)->willReturn($this->lines);
        $this->beConstructedWith($converter);
    }

    function it_is_a_replace_strategy()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Replace\ReplaceStrategy');
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

    function it_removes_lines(File $file, LineContentConverter $converter)
    {
        $expectedFilename = sprintf(self::ORIGINAL_FILENAME, $this->rootPath);
        $expectedLines = file($expectedFilename, FILE_IGNORE_NEW_LINES);

        $lineNumber = 1;
        unset($expectedLines[$lineNumber]);

        $converter->back($file, $expectedLines)->shouldBeCalled();
        $this->removeAt($file, $lineNumber);
    }

    function it_replaces_line(File $file, LineContentConverter $converter)
    {
        $expectedFilename = sprintf(self::EXPECTED_FILENAME, $this->rootPath, 'replace');
        $expectedLines = file($expectedFilename, FILE_IGNORE_NEW_LINES);

        $replacement = "[Even more sniggering]";
        $lineNumber = 5;

        $converter->back($file, $expectedLines)->shouldBeCalled();
        $this->replaceWith($file, $lineNumber, $replacement);
    }

    function it_inserts_new_lines(File $file, LineContentConverter $converter)
    {
        $expectedFilename = sprintf(self::EXPECTED_FILENAME, $this->rootPath, 'insert');
        $expectedLines = file($expectedFilename, FILE_IGNORE_NEW_LINES);

        $addition = "Pontius Pilate: '...Dickus?'";
        $lineNumber = 6;

        $converter->back($file, $expectedLines)->shouldBeCalled();
        $this->insertAt($file, $lineNumber, $addition);
    }
}
