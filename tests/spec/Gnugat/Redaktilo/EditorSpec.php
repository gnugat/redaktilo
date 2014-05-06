<?php

namespace spec\Gnugat\Redaktilo;

use Gnugat\Redaktilo\File\Filesystem;
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
        $filesystem->read(self::FILENAME)->willReturn($file);
        $this->open(self::FILENAME);

        // Looking for the line "knights"
        $file->getLines()->willReturn($beforeLines);
        $file->getFilename()->willReturn('/monthy/python.txt');
        $file->getCurrentLineNumber()->willReturn(0);
        $file->setCurrentLineNumber(2)->shouldBeCalled();
        $this->jumpDownTo($file, 'knights');

        // Inserting the line "the" before the line "knights"
        $file->getCurrentLineNumber()->willReturn(2);
        $file->write($afterLines)->shouldBeCalled();
        $this->addBefore($file, 'the');

        // Saving the file
        $filesystem->write($file)->shouldBeCalled();
        $this->save($file);
    }
}
