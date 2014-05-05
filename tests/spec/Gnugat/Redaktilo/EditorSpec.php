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
        $beforeLines = array('We', 'are', 'knights', 'who', 'say', 'ni');
        $afterLines = array('We', 'are', 'the', 'knights', 'who', 'say', 'ni');

        $file->getLines()->willReturn($beforeLines);
        $file->getFilename()->willReturn('/monthy/python.txt');
        $filesystem
            ->read(self::FILENAME, Filesystem::LINE_FILE_TYPE)
            ->willReturn($file)
        ;

        $file->write($afterLines)->shouldBeCalled();
        $filesystem->write($file)->shouldBeCalled();

        $this->open(self::FILENAME);
        $this->jumpDownTo('knights');
        $this->addBefore('the');
        $this->save();
    }
}
