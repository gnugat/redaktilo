<?php

namespace spec\Gnugat\Redaktilo;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem as FileCopier;

class FileSpec extends ObjectBehavior
{
    const FILENAME = '%s/tests/fixtures/%s/%s';

    private $filename;
    private $content;

    function let()
    {
        $rootPath = __DIR__.'/../../../../';

        $sourceFilename = sprintf(self::FILENAME, $rootPath, 'sources', 'copy-me.txt');
        $copyFilename = sprintf(self::FILENAME, $rootPath, 'copies', 'edit-me.txt');

        $fileCopier = new FileCopier();
        $fileCopier->copy($sourceFilename, $copyFilename, true);

        $this->filename = $copyFilename;
        $this->content = file_get_contents($copyFilename);

        $this->beConstructedWith($this->filename, $this->content);
    }

    function it_has_a_filename()
    {
        $this->getFilename()->shouldBe($this->filename);
    }

    function it_has_a_content()
    {
        $this->getContent()->shouldBe($this->content);
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
