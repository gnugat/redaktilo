<?php

namespace spec\Gnugat\Redaktilo;

use Gnugat\Redaktilo\Filesystem\Filesystem;
use Gnugat\Redaktilo\File;
use PhpSpec\ObjectBehavior;

class EditorSpec extends ObjectBehavior
{
    const FILENAME = '/tmp/file-to-edit.txt';

    function let(Filesystem $filesystem)
    {
        $this->beConstructedWith($filesystem);
    }

    function it_inserts_lines_before_cursor(Filesystem $filesystem, File $file)
    {
        // Fixtures
        $beforeLines = array('We', 'are', 'knights', 'who', 'say', 'ni');
        $afterLines = array('We', 'are', 'the', 'knights', 'who', 'say', 'ni');

        // Openning
        $filesystem->open(self::FILENAME)->willReturn($file);
        $this->open(self::FILENAME);

        // Looking for the line "knights"
        $file->getLines()->willReturn($beforeLines);
        $file->getFilename()->willReturn('/monthy/python.txt');
        $file->getCurrentLineNumber()->willReturn(0);
        $file->setCurrentLineNumber(2)->shouldBeCalled();
        $this->jumpDownTo($file, 'knights');

        // Saving the file
        $filesystem->write($file)->shouldBeCalled();
        $this->save($file);
    }

    function it_inserts_lines_before_current_one(File $file)
    {
        $newLine = 'We are the knights who say Ni!';
        $file->insertBefore($newLine)->shouldBeCalled();
        $this->addBefore($file, $newLine);
    }

    function it_inserts_lines_after_current_one(File $file)
    {
        $newLine = 'We are the knights who say Ni!';
        $file->insertAfter($newLine)->shouldBeCalled();
        $this->addAfter($file, $newLine);
    }
}
