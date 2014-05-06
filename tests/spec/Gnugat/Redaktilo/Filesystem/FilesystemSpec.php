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

    function it_creates_new_files()
    {
        @unlink($this->copyFilename);

        $file = $this->create($this->copyFilename);

        $file->shouldHaveType('Gnugat\Redaktilo\File');
    }

    function it_cannot_create_existing_files()
    {
        $this->fileCopier->copy($this->sourceFilename, $this->copyFilename, true);

        $exception = 'Symfony\Component\Filesystem\Exception\IOException';
        $this->shouldThrow($exception)->duringCreate($this->copyFilename);
    }

    function it_detects_if_file_exists()
    {
        @unlink($this->copyFilename);

        $this->exists($this->copyFilename)->shouldBe(false);

        $this->fileCopier->copy($this->sourceFilename, $this->copyFilename, true);

        $this->exists($this->copyFilename)->shouldBe(true);
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
