<?php

namespace spec\Gnugat\Redaktilo;

use PhpSpec\ObjectBehavior;

class FileSpec extends ObjectBehavior
{
    function let()
    {
        $sourceFilename = __DIR__.'/../../../fixtures/sources/copy-me.txt';
        $copyFilename = __DIR__.'/../../../fixtures/copies/edit-me.txt';
        $content = file_get_contents($sourceFilename);

        $this->beConstructedWith($copyFilename, $content);
    }

    function it_reads_content()
    {
        $content = array(
            'We are the knigths who say ni!',
            '',
        );

        $this->read()->shouldReturn($content);
    }

    function it_writes_content()
    {
        $content = array(
            'We are the knigths who say ni!',
            'Grumpy',
            'Cat',
            '',
        );

        $this->write($content);
        $this->read()->shouldBe($content);
    }
}
