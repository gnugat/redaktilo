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

use Gnugat\Redaktilo\Text;
use PhpSpec\ObjectBehavior;

class LinePrependCommandSpec extends ObjectBehavior
{
    const ORIGINAL_FILENAME = '%s/tests/fixtures/sources/to-indent.py';
    const EXPECTED_FILENAME = '%s/tests/fixtures/expectations/to-indent.py';

    const LINE_NUMBER = 1;

    private $rootPath;
    private $lines;

    function let(Text $text)
    {
        $this->rootPath = __DIR__.'/../../../../../';

        $filename = sprintf(self::ORIGINAL_FILENAME, $this->rootPath);
        $this->lines = file($filename, FILE_IGNORE_NEW_LINES);
    }

    function it_is_a_command()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Command\Command');
    }

    function it_appends_a_string_to_the_line(Text $text)
    {
        $expectedFilename = sprintf(self::EXPECTED_FILENAME, $this->rootPath);
        $expectedLines = file($expectedFilename, FILE_IGNORE_NEW_LINES);

        $input = array(
            'text' => $text,
            'value' => '    ',
            'location' => self::LINE_NUMBER,
        );
        $text->getLines()->willReturn($this->lines);
        $text->setLines($expectedLines)->shouldBeCalled();

        $this->execute($input);

        $input = array(
            'text' => $text,
            'value' => '    ',
        );
        $text->getLines()->willReturn($this->lines);
        $text->getCurrentLineNumber()->willReturn(self::LINE_NUMBER);
        $text->setLines($expectedLines)->shouldBeCalled();

        $this->execute($input);
    }
}
