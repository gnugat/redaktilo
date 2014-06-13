<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Command;

use Gnugat\Redaktilo\Converter\LineContentConverter;
use Gnugat\Redaktilo\File;
use PhpSpec\ObjectBehavior;

class LineInsertAboveCommandSpec extends ObjectBehavior
{
    const ORIGINAL_FILENAME = '%s/tests/fixtures/sources/life-of-brian.txt';
    const EXPECTED_FILENAME = '%s/tests/fixtures/expectations/life-of-brian-insert.txt';

    private $rootPath;
    private $converter;

    function let(File $file, LineContentConverter $converter)
    {
        $this->rootPath = __DIR__.'/../../../../../';
        $this->converter = $converter;

        $filename = sprintf(self::ORIGINAL_FILENAME, $this->rootPath);
        $lines = file($filename, FILE_IGNORE_NEW_LINES);

        $this->converter->from($file)->willReturn($lines);
        $this->beConstructedWith($this->converter);
    }

    function it_is_a_command()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Command\Command');
    }

    function it_inserts_new_lines(File $file)
    {
        $expectedFilename = sprintf(self::EXPECTED_FILENAME, $this->rootPath);
        $expectedLines = file($expectedFilename, FILE_IGNORE_NEW_LINES);

        $lineNumber = 6;

        $input = array(
            'file' => $file,
            'location' => $lineNumber,
            'addition' => "Pontius Pilate: '...Dickus?'"
        );

        $this->converter->back($file, $expectedLines)->shouldBeCalled();
        $file->setCurrentLineNumber($lineNumber)->shouldBeCalled();

        $this->execute($input);

        $input = array(
            'file' => $file,
            'addition' => "Pontius Pilate: '...Dickus?'"
        );
        $file->getCurrentLineNumber()->willReturn($lineNumber);
        $file->setCurrentLineNumber($lineNumber)->shouldBeCalled();

        $this->execute($input);
    }
}
