<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo;

use Gnugat\Redaktilo\Engine\ReplaceEngine;
use Gnugat\Redaktilo\Engine\SearchEngine;
use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Filesystem;
use Gnugat\Redaktilo\Replace\ReplaceStrategy;
use Gnugat\Redaktilo\Search\SearchStrategy;
use PhpSpec\ObjectBehavior;

class EditorSpec extends ObjectBehavior
{
    const FILENAME = '/tmp/file-to-edit.txt';

    function let(
        Filesystem $filesystem,
        SearchEngine $searchEngine,
        ReplaceEngine $replaceEngine
    )
    {
        $this->beConstructedWith(
            $filesystem,
            $searchEngine,
            $replaceEngine
        );
    }

    function it_opens_existing_files(Filesystem $filesystem, File $file)
    {
        $filename = '/monty.py';

        $filesystem->exists($filename)->willReturn(true);
        $filesystem->open($filename)->willReturn($file);

        $this->open($filename);
    }

    function it_cannot_open_new_files(Filesystem $filesystem, File $file)
    {
        $filename = '/monty.py';
        $exception = 'Symfony\Component\Filesystem\Exception\FileNotFoundException';

        $filesystem->exists($filename)->willReturn(false);
        $filesystem->open($filename)->willThrow($exception);

        $this->shouldThrow($exception)->duringOpen($filename);
    }

    function it_creates_new_files(Filesystem $filesystem, File $file)
    {
        $filename = '/monty.py';

        $filesystem->exists($filename)->willReturn(false);
        $filesystem->create($filename)->willReturn($file);

        $this->open($filename, true);
    }

    function it_moves_down_the_cursor(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        File $file
    )
    {
        $pattern = 'No one expects the Spanish inquisition!';
        $foundLineNumber = 42;

        $searchEngine->resolve($pattern)->willReturn($searchStrategy);
        $searchStrategy->findNext($file, $pattern)->willReturn($foundLineNumber);
        $file->setCurrentLineNumber($foundLineNumber)->shouldBeCalled();

        $this->jumpDownTo($file, $pattern);
    }

    function it_moves_up_the_cursor(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        File $file
    )
    {
        $pattern = 'Nobody expects the Spanish Inquisition!';
        $foundLineNumber = 4423;

        $searchEngine->resolve($pattern)->willReturn($searchStrategy);
        $searchStrategy->findPrevious($file, $pattern)->willReturn($foundLineNumber);
        $file->setCurrentLineNumber($foundLineNumber)->shouldBeCalled();

        $this->jumpUpTo($file, $pattern);
    }

    function it_checks_pattern_existence(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        File $file
    )
    {
        $pattern = 'No one expects the spanish inquisition!';

        $searchEngine->resolve($pattern)->willReturn($searchStrategy);
        $searchStrategy->has($file, $pattern)->willReturn(true);

        $this->has($file, $pattern)->shouldBe(true);
    }

    function it_inserts_lines_before_current_one(
        ReplaceEngine $replaceEngine,
        ReplaceStrategy $replaceStrategy,
        File $file
    )
    {
        $currentLineNumber = 42;
        $location = $currentLineNumber;
        $addition = 'We are the knights who say Ni!';

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);
        $replaceEngine->resolve($location)->willReturn($replaceStrategy);
        $replaceStrategy->insertAt($file, $location, $addition)->shouldBeCalled();

        $this->addBefore($file, $addition);
    }

    function it_inserts_lines_after_current_one(
        ReplaceEngine $replaceEngine,
        ReplaceStrategy $replaceStrategy,
        File $file
    )
    {
        $currentLineNumber = 42;
        $location = $currentLineNumber + 1;
        $addition = 'We are the knights who say Ni!';

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);
        $replaceEngine->resolve($location)->willReturn($replaceStrategy);
        $replaceStrategy->insertAt($file, $location, $addition)->shouldBeCalled();

        $this->addAfter($file, $addition);
    }

    function it_changes_the_current_line(
        ReplaceEngine $replaceEngine,
        ReplaceStrategy $replaceStrategy,
        File $file
    )
    {
        $currentLineNumber = 42;
        $location = $currentLineNumber;
        $replacement = 'We are knights who say Ni!';

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);
        $replaceEngine->resolve($location)->willReturn($replaceStrategy);
        $replaceStrategy->replaceWith($file, $location, $replacement)->shouldBeCalled();

        $this->changeTo($file, $replacement);
    }

    function it_replaces_the_current_line(File $file)
    {
        $line = 'We are the knights who say Ni!';
        $newLine = 'We are the knights who say Peng!';
        $lineNumber = 0;

        $file->getCurrentLineNumber()->willReturn($lineNumber);
        $file->read()->willReturn($line);
        $file->changeLineTo($newLine, $lineNumber)->shouldBeCalled();

        $this->replaceWith($file, '/Ni/', 'Peng');
    }

    function it_removes_the_current_line(
        ReplaceEngine $replaceEngine,
        ReplaceStrategy $replaceStrategy,
        File $file
    )
    {
        $currentLineNumber = 42;
        $location = $currentLineNumber;

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);
        $replaceEngine->resolve($location)->willReturn($replaceStrategy);
        $replaceStrategy->removeAt($file, $location)->shouldBeCalled();

        $this->remove($file);
    }

    function it_saves_files(Filesystem $filesystem, File $file)
    {
        $filesystem->write($file)->shouldBeCalled();

        $this->save($file);
    }
}
