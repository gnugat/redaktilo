<?php

namespace spec\Gnugat\Redaktilo;

use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Filesystem;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class FilesystemSpec extends ObjectBehavior
{
    private $sourceFilename;
    private $copyFilename;

    private $fileCopier;

    function let(SymfonyFilesystem $symfonyFilesystem)
    {
        $this->sourceFilename = __DIR__.'/../../../fixtures/sources/copy-me.txt';
        $this->copyFilename = __DIR__.'/../../../fixtures/copies/edit-me.txt';

        $this->fileCopier = new SymfonyFilesystem();

        $this->beConstructedWith($symfonyFilesystem);
    }

    function it_detects_file_line_break()
    {
        $lines = array(
            'King Arthur: One, two, five!',
            'Sir Galahad: Three sir!',
            'King Arthur: THREE!',
        );
        $fileWithoutLines = $lines[0];
        $windowsFile = implode(Filesystem::LINE_BREAK_WINDOWS, $lines);
        $otherFile = implode(Filesystem::LINE_BREAK_OTHER, $lines);

        $this->detectLineBreak($fileWithoutLines)->shouldBe(PHP_EOL);
        $this->detectLineBreak($windowsFile)->shouldBe(Filesystem::LINE_BREAK_WINDOWS);
        $this->detectLineBreak($otherFile)->shouldBe(Filesystem::LINE_BREAK_OTHER);
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

    function it_writes_files(SymfonyFilesystem $symfonyFilesystem)
    {
        $this->fileCopier->copy($this->sourceFilename, $this->copyFilename, true);
        $content = file_get_contents($this->copyFilename);
        $file = new File($this->copyFilename, $content);

        $symfonyFilesystem->dumpFile($this->copyFilename, $content, null)->shouldBeCalled();

        $this->write($file);
    }
}
