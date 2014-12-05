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

class FileSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('fromString', array('Hello World'));
    }

    function it_is_a_text()
    {
        $this->shouldBeAnInstanceOf('Gnugat\Redaktilo\Text');
    }

    function it_can_have_a_filename()
    {
        $this->setFilename('tmp/old.txt');
        $this->getFilename()->shouldBe('tmp/old.txt');

        $this->setFilename('tmp/new.txt');
        $this->getFilename()->shouldBe('tmp/new.txt');
    }
}
