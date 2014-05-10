<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\SearchEngine;

use Gnugat\Redaktilo\File;
use PhpSpec\ObjectBehavior;

class LineSearchEngineSpec extends ObjectBehavior
{
    const FILENAME = '%s/tests/fixtures/sources/life-of-brian.txt';

    function let(File $file)
    {
        $rootPath = __DIR__.'/../../../../../';

        $filename = sprintf(self::FILENAME, $rootPath);
        $lines = file($filename, FILE_IGNORE_NEW_LINES);

        $file->readlines()->willReturn($lines);
    }

    function it_implements_search_engine()
    {
        $this->shouldImplement('Gnugat\Redaktilo\SearchEngine\SearchEngine');
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
}
