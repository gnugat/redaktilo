<?php

namespace spec\Gnugat\Redaktilo\File;

use Gnugat\Redaktilo\File\Filesystem;
use Gnugat\Redaktilo\File\LineFile;
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

    function it_reads_line_files()
    {
        $this->fileCopier->copy($this->sourceFilename, $this->copyFilename, true);

        $file = $this->read($this->copyFilename, Filesystem::LINE_FILE_TYPE);

        $file->shouldHaveType('Gnugat\Redaktilo\File\LineFile');
        $file->shouldImplement('Gnugat\Redaktilo\File\File');
    }

    function it_writes_line_files()
    {
        $content = <<< EOS
We are the knigths who say ni!
Grumpy
Cat
EOS;
        $file = new LineFile($this->copyFilename, $content);

        $this->write($file);

        $wroteContent = file_get_contents($this->copyFilename);
        expect($wroteContent)->toBe($content);
    }
}
