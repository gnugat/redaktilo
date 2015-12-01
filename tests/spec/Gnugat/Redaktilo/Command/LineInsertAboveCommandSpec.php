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

class LineInsertAboveCommandSpec extends ObjectBehavior
{
    const ORIGINAL_FILENAME = '%s/tests/fixtures/sources/life-of-brian.txt';
    const EXPECTED_FILENAME = '%s/tests/fixtures/expectations/life-of-brian-insert.txt';

    private $rootPath;

    function let(TextSanitizer $textSanitizer, LocationSanitizer $locationSanitizer, Text $text)
    {
        $this->rootPath = __DIR__.'/../../../../../';

        $filename = sprintf(self::ORIGINAL_FILENAME, $this->rootPath);
        $lines = file($filename, FILE_IGNORE_NEW_LINES);

        $text->getLines()->willReturn($lines);

        $this->beConstructedWith(
            $textSanitizer,
            $locationSanitizer
        );
    }

    function it_is_a_command()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Command\Command');
    }

    function it_inserts_new_lines(TextSanitizer $textSanitizer, LocationSanitizer $locationSanitizer, Text $text)
    {
        $expectedFilename = sprintf(self::EXPECTED_FILENAME, $this->rootPath);
        $expectedLines = file($expectedFilename, FILE_IGNORE_NEW_LINES);

        $lineNumber = 6;

        $input = array(
            'text' => $text,
            'location' => $lineNumber,
            'addition' => "Pontius Pilate: '...Dickus?'"
        );

        $textSanitizer->sanitize($input)->willReturn($text);
        $locationSanitizer->sanitize($input)->willReturn($lineNumber);

        $text->setLines($expectedLines)->shouldBeCalled();
        $text->setCurrentLineNumber($lineNumber)->shouldBeCalled();

        $this->execute($input);

        $input = array(
            'text' => $text,
            'addition' => "Pontius Pilate: '...Dickus?'"
        );

        $textSanitizer->sanitize($input)->willReturn($text);
        $locationSanitizer->sanitize($input)->willReturn($lineNumber);

        $text->getCurrentLineNumber()->willReturn($lineNumber);
        $text->setCurrentLineNumber($lineNumber)->shouldBeCalled();

        $this->execute($input);
    }
}
