<?php

namespace spec\Gnugat\Redaktilo;

use Gnugat\Redaktilo\File;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem as FileCopier;

class FileSpec extends ObjectBehavior
{
    const FILENAME = '%s/tests/fixtures/%s/life-of-brian.txt';

    private $filename;
    private $content;

    function let()
    {
        $rootPath = __DIR__.'/../../../../';

        $sourceFilename = sprintf(self::FILENAME, $rootPath, 'sources');
        $copyFilename = sprintf(self::FILENAME, $rootPath, 'copies');

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

    function it_has_lines()
    {
        $lines = explode(PHP_EOL, $this->content);

        $this->getLines()->shouldBe($lines);
    }

    function it_has_a_current_line()
    {
        $this->getCurrentLineNumber()->shouldBe(0);

        $lines = explode(PHP_EOL, $this->content);
        $middleLine = count($lines) / 2;

        $this->setCurrentLineNumber($middleLine);
        $this->getCurrentLineNumber()->shouldBe($middleLine);
    }

    function it_inserts_lines()
    {
        $rootPath = __DIR__.'/../../../../';
        $expectedFilename = sprintf(self::FILENAME, $rootPath, 'expectations');
        $expectedContent = file_get_contents($expectedFilename);

        $line = "Pontius Pilate: '...Dickus?'";
        $lineNumber = 6;

        $this->insertLineAt($line, $lineNumber);
        $this->getcontent()->shouldBe($expectedContent);
    }

    function it_inserts_new_lines_before_the_current_one()
    {
        $newLineNumber = 6;
        $newLine = "Pontius Pilate: '...'";

        $expectedLines = explode(PHP_EOL, $this->content);
        array_splice($expectedLines, $newLineNumber, 0, $newLine);

        $this->setCurrentLineNumber($newLineNumber);
        $this->insertBefore($newLine);

        $this->getLines()->shouldBe($expectedLines);
        $this->getCurrentLineNumber()->shouldBe($newLineNumber);
    }

    function it_inserts_new_lines_after_the_current_one()
    {
        $newLineNumber = 2;
        $newLine = "Pontius Pilate: '...'";

        $expectedLines = explode(PHP_EOL, $this->content);
        array_splice($expectedLines, $newLineNumber, 0, $newLine);

        $this->setCurrentLineNumber($newLineNumber - 1);
        $this->insertAfter($newLine);

        $this->getLines()->shouldBe($expectedLines);
        $this->getCurrentLineNumber()->shouldBe($newLineNumber);
    }
}
