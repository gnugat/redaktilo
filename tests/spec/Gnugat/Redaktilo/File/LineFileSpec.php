<?php

namespace spec\Gnugat\Redaktilo\File;

use PhpSpec\ObjectBehavior;

class LineFileSpec extends ObjectBehavior
{
    private $sourceFilename;
    private $copyFilename;

    function let()
    {
        $this->sourceFilename = __DIR__.'/../../../../fixtures/sources/copy-me.txt';
        $this->copyFilename = __DIR__.'/../../../../fixtures/copies/edit-me.txt';
        $content = file_get_contents($this->sourceFilename);

        $this->beConstructedWith($this->copyFilename, $content);
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
