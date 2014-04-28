<?php

namespace spec\Gnugat\Redaktilo\Editor;

use Gnugat\Redaktilo\File\Filesystem;
use Gnugat\Redaktilo\File\LineFile;
use PhpSpec\ObjectBehavior;

class LineEditorSpec extends ObjectBehavior
{
    const FILENAME = '/tmp/file-to-edit.txt';

    function let(Filesystem $filesystem)
    {
        $this->beConstructedWith($filesystem);
    }

    function it_edits_files(Filesystem $filesystem, LineFile $file)
    {
        $beforeLines = array(
            'Grumpy',
            '',
        );
        $afterLines = array(
            'Grumpy',
            'Cat',
            '',
        );

        $file->read()->willReturn($beforeLines);
        $filesystem
            ->read(self::FILENAME, Filesystem::LINE_FILE_TYPE)
            ->willReturn($file)
        ;

        $file->write($afterLines)->shouldBeCalled();
        $filesystem->write($file)->shouldBeCalled();

        $this->open(self::FILENAME);
        $this->addAfter('Cat', 'Grumpy');
    }
}
