<?php

namespace spec\Gnugat\Redaktilo;

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

    function it_writes_content()
    {
        $content = explode(PHP_EOL, $this->content);

        $this->write($content);
        $this->getLines()->shouldBe($content);
    }
}
