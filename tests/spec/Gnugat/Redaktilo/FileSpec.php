<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem as FileCopier;

class FileSpec extends ObjectBehavior
{
    private $filename;

    function let()
    {
        $this->filename = '/tmp/egg.txt';
        $lines = array();
        $lineBreak = "\n";

        $this->beConstructedWith($this->filename, $lines, $lineBreak);
    }

    function it_is_a_text()
    {
        $this->shouldBeAnInstanceOf('Gnugat\Redaktilo\Text');
    }

    function it_has_a_filename()
    {
        $newFilename = '/tmp/spam.txt';

        $this->getFilename()->shouldBe($this->filename);
        $this->setFilename($newFilename);
        $this->getFilename()->shouldBe($newFilename);
    }
}
