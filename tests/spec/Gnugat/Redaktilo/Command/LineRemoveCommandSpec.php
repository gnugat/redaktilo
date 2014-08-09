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

use Gnugat\Redaktilo\File;
use PhpSpec\ObjectBehavior;

class LineRemoveCommandSpec extends ObjectBehavior
{
    const ORIGINAL_FILENAME = '%s/tests/fixtures/sources/life-of-brian.txt';

    private $rootPath;

    function let(File $file)
    {
        $this->rootPath = __DIR__.'/../../../../../';

        $filename = sprintf(self::ORIGINAL_FILENAME, $this->rootPath);
        $lines = file($filename, FILE_IGNORE_NEW_LINES);

        $file->getLines()->willReturn($lines);
        $this->beConstructedWith();
    }

    function it_is_a_command()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Command\Command');
    }

    function it_removes_lines(File $file)
    {
        $expectedFilename = sprintf(self::ORIGINAL_FILENAME, $this->rootPath);
        $expectedLines = file($expectedFilename, FILE_IGNORE_NEW_LINES);

        $lineNumber = 2;

        unset($expectedLines[$lineNumber]);

        $input = array(
            'file' => $file,
            'location' => $lineNumber
        );

        $file->setLines($expectedLines)->shouldBeCalled();
        $file->setCurrentLineNumber($lineNumber)->shouldBeCalled();

        $this->execute($input);

        $input = array(
            'file' => $file,
        );
        $file->getCurrentLineNumber()->willReturn($lineNumber);
        $file->setCurrentLineNumber($lineNumber)->shouldBeCalled();

        $this->execute($input);
    }
}
