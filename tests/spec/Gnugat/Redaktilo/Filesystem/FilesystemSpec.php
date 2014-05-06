<?php

namespace spec\Gnugat\Redaktilo\Filesystem;

use Gnugat\Redaktilo\File\Filesystem;
use Gnugat\Redaktilo\File;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem as FileCopier;

class FilesystemSpec extends ObjectBehavior
{
    private $sourceFilename;
    private $copyFilename;

    private $fileCopier;

    function let()
    {
        $this->sourceFilename = __DIR__.'/../../../../fixtures/sources/copy-me.txt';
        $this->copyFilename = __DIR__.'/../../../../fixtures/copies/edit-me.txt';

        $this->fileCopier = new FileCopier();
    }

    function it_opens_existing_files()
    {
        $this->fileCopier->copy($this->sourceFilename, $this->copyFilename, true);

        $file = $this->open($this->copyFilename);

        $file->shouldHaveType('Gnugat\Redaktilo\File');
    }

    function it_cannot_open_new_files()
    {
        @unlink($this->copyFilename);

        $exception = 'Symfony\Component\Filesystem\Exception\FileNotFoundException';
        $this->shouldThrow($exception)->duringOpen($this->copyFilename);
    }

    function it_writes_files()
    {
        $content = <<< EOS
We are the knigths who say ni!
Grumpy
Cat
EOS;
        $file = new File($this->copyFilename, $content);

        $this->write($file);

        $wroteContent = file_get_contents($this->copyFilename);
        expect($wroteContent)->toBe($content);
    }
}
