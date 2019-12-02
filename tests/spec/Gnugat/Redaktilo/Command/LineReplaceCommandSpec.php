<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Command;

use Gnugat\Redaktilo\Command\Sanitizer\LocationSanitizer;
use Gnugat\Redaktilo\Command\Sanitizer\TextSanitizer;
use Gnugat\Redaktilo\Text;
use PhpSpec\ObjectBehavior;

class LineReplaceCommandSpec extends ObjectBehavior
{
    const ORIGINAL_FILENAME = '%s/tests/fixtures/sources/life-of-brian.txt';
    const EXPECTED_FILENAME = '%s/tests/fixtures/expectations/life-of-brian-replace.txt';

    const LINE_NUMBER = 5;

    private $rootPath;

    function let(TextSanitizer $textSanitizer, LocationSanitizer $locationSanitizer, Text $text)
    {
        $this->rootPath = __DIR__.'/../../../../../';

        $filename = sprintf(self::ORIGINAL_FILENAME, $this->rootPath);
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        $line = $lines[self::LINE_NUMBER];

        $text->getLine(self::LINE_NUMBER)->willReturn($line);

        $this->beConstructedWith(
            $textSanitizer,
            $locationSanitizer
        );
    }

    function it_is_a_command()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Command\Command');
    }

    function it_replaces_line(TextSanitizer $textSanitizer, LocationSanitizer $locationSanitizer, Text $text)
    {
        $expectedFilename = sprintf(self::EXPECTED_FILENAME, $this->rootPath);
        $expectedLines = file($expectedFilename, FILE_IGNORE_NEW_LINES);
        $expectedLine = $expectedLines[self::LINE_NUMBER];
        $replacement = function ($line) {
            return '[Even more sniggering]';
        };

        $input = [
            'text' => $text,
            'location' => self::LINE_NUMBER,
            'replacement' => $replacement,
        ];

        $textSanitizer->sanitize($input)->willReturn($text);
        $locationSanitizer->sanitize($input)->willReturn(self::LINE_NUMBER);

        $text->setLine($expectedLine, self::LINE_NUMBER)->shouldBeCalled();
        $text->setCurrentLineNumber(self::LINE_NUMBER)->shouldBeCalled();

        $this->execute($input);

        $input = [
            'text' => $text,
            'replacement' => $replacement,
        ];

        $textSanitizer->sanitize($input)->willReturn($text);
        $locationSanitizer->sanitize($input)->willReturn(self::LINE_NUMBER);

        $text->getCurrentLineNumber()->willReturn(self::LINE_NUMBER);
        $text->setCurrentLineNumber(self::LINE_NUMBER)->shouldBeCalled();

        $this->execute($input);
    }
}
