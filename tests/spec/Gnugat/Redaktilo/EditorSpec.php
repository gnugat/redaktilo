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

use Gnugat\Redaktilo\Command\CommandInvoker;
use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Filesystem;
use Gnugat\Redaktilo\Search\SearchEngine;
use Gnugat\Redaktilo\Search\SearchStrategy;
use PhpSpec\ObjectBehavior;

class EditorSpec extends ObjectBehavior
{
    const FILENAME = '/tmp/file-to-edit.txt';

    function let(
        File $file,
        Filesystem $filesystem,
        SearchEngine $searchEngine,
        CommandInvoker $commandInvoker
    )
    {
        $file->getFilename()->willReturn(self::FILENAME);

        $this->beConstructedWith(
            $filesystem,
            $searchEngine,
            $commandInvoker
        );
    }

    function it_opens_existing_files(Filesystem $filesystem, File $file)
    {
        $filesystem->exists(self::FILENAME)->willReturn(true);
        $filesystem->open(self::FILENAME)->willReturn($file);

        $this->open(self::FILENAME);
    }

    function it_cannot_open_new_files(Filesystem $filesystem, File $file)
    {
        $exception = 'Symfony\Component\Filesystem\Exception\FileNotFoundException';

        $filesystem->exists(self::FILENAME)->willReturn(false);
        $filesystem->open(self::FILENAME)->willThrow($exception);

        $this->shouldThrow($exception)->duringOpen(self::FILENAME);
    }

    function it_creates_new_files(Filesystem $filesystem, File $file)
    {
        $filesystem->exists(self::FILENAME)->willReturn(false);
        $filesystem->create(self::FILENAME)->willReturn($file);

        $this->open(self::FILENAME, true);
    }

    function it_moves_the_cursor_to_the_first_occurence(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        File $file
    )
    {
        $absoluteLine = 42;

        $searchEngine->resolve($absoluteLine)->willReturn($searchStrategy);
        $searchStrategy->findNext($file, $absoluteLine, 0)->willReturn($absoluteLine);
        $file->setCurrentLineNumber($absoluteLine)->shouldBeCalled();

        $this->jumpTo($file, $absoluteLine);

        $searchStrategy->findNext($file, $absoluteLine, 0)->willReturn(false);
        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';
        $this->shouldThrow($exception)->duringJumpTo($file, $absoluteLine);
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

        $searchStrategy->findNext($file, $pattern)->willReturn(false);
        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';
        $this->shouldThrow($exception)->duringJumpDownTo($file, $pattern);
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

        $searchStrategy->findPrevious($file, $pattern)->willReturn(false);
        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';
        $this->shouldThrow($exception)->duringJumpUpTo($file, $pattern);
    }

    function it_checks_pattern_existence(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        File $file
    )
    {
        $pattern = 'No one expects the spanish inquisition!';

        $searchEngine->resolve($pattern)->willReturn($searchStrategy);
        $searchStrategy->findNext($file, $pattern, 0)->willReturn(42);

        $this->has($file, $pattern)->shouldBe(true);
    }

    function it_inserts_lines_before_current_one(
        CommandInvoker $commandInvoker,
        File $file
    )
    {
        $currentLineNumber = 42;
        $addition = 'We are the knights who say Ni!';
        $input = array(
            'file' => $file,
            'location' =>$currentLineNumber,
            'addition' => $addition,
        );

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);
        $commandInvoker->run('insert', $input)->shouldBeCalled();
        $file->setCurrentLineNumber($currentLineNumber)->shouldBeCalled();

        $this->addBefore($file, $addition);
    }

    function it_inserts_lines_before_given_one(
        CommandInvoker $commandInvoker,
        File $file
    )
    {
        $lineNumber = 43;
        $addition = 'We are the knights who say Ni!';
        $input = array(
            'file' => $file,
            'location' => $lineNumber,
            'addition' => $addition,
        );

        $file->getCurrentLineNumber()->shouldNotBeCalled();
        $commandInvoker->run('insert', $input)->shouldBeCalled();
        $file->setCurrentLineNumber($lineNumber)->shouldBeCalled();

        $this->addBefore($file, $addition, $lineNumber);
    }

    function it_inserts_lines_after_current_one(
        CommandInvoker $commandInvoker,
        File $file
    )
    {
        $currentLineNumber = 42;
        $addition = 'We are the knights who say Ni!';
        $input = array(
            'file' => $file,
            'location' => $currentLineNumber + 1,
            'addition' => $addition,
        );

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);
        $commandInvoker->run('insert', $input)->shouldBeCalled();
        $file->setCurrentLineNumber($currentLineNumber + 1)->shouldBeCalled();

        $this->addAfter($file, $addition);
    }

    function it_inserts_lines_after_given_one(
        CommandInvoker $commandInvoker,
        File $file
    )
    {
        $lineNumber = 43;
        $addition = 'We are the knights who say Ni!';
        $input = array(
            'file' => $file,
            'location' => $lineNumber + 1,
            'addition' => $addition,
        );

        $file->getCurrentLineNumber()->shouldNotBeCalled();
        $commandInvoker->run('insert', $input)->shouldBeCalled();
        $file->setCurrentLineNumber($lineNumber + 1)->shouldBeCalled();

        $this->addAfter($file, $addition, $lineNumber);
    }

    function it_changes_the_current_line(
        CommandInvoker $commandInvoker,
        File $file
    )
    {
        $currentLineNumber = 42;
        $replacement = 'We are knights who say Ni!';
        $input = array(
            'file' => $file,
            'location' => $currentLineNumber,
            'replacement' => $replacement,
        );

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);
        $commandInvoker->run('replace', $input)->shouldBeCalled();
        $file->setCurrentLineNumber($currentLineNumber)->shouldBeCalled();

        $this->changeTo($file, $replacement);
    }

    function it_changes_the_given_line(
        CommandInvoker $commandInvoker,
        File $file
    )
    {
        $lineNumber = 43;
        $replacement = 'We are knights who say Ni!';
        $input = array(
            'file' => $file,
            'location' => $lineNumber,
            'replacement' => $replacement,
        );

        $file->getCurrentLineNumber()->shouldNotBeCalled();
        $commandInvoker->run('replace', $input)->shouldBeCalled();
        $file->setCurrentLineNumber($lineNumber)->shouldBeCalled();

        $this->changeTo($file, $replacement, $lineNumber);
    }

    function it_replaces_the_current_line(File $file)
    {
        $line = 'We are the knights who say Ni!';
        $newLine = 'We are the knights who say Peng!';
        $currentLineNumber = 0;

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);
        $file->read()->willReturn($line);
        $file->changeLineTo($newLine, $currentLineNumber)->shouldBeCalled();
        $file->setCurrentLineNumber($currentLineNumber)->shouldBeCalled();

        $this->replaceWith($file, '/Ni/', 'Peng');
    }

    function it_replaces_the_given_line(File $file)
    {
        $line = 'We are the knights who say Ni!';
        $newLine = 'We are the knights who say Peng!';
        $lineNumber = 0;

        $file->getCurrentLineNumber()->shouldNotBeCalled();
        $file->read()->willReturn($line);
        $file->changeLineTo($newLine, $lineNumber)->shouldBeCalled();
        $file->setCurrentLineNumber($lineNumber)->shouldBeCalled();

        $this->replaceWith($file, '/Ni/', 'Peng', $lineNumber);
    }

    function it_removes_the_current_line(
        CommandInvoker $commandInvoker,
        File $file
    )
    {
        $currentLineNumber = 42;
        $input = array(
            'file' => $file,
            'location' => $currentLineNumber,
        );

        $file->getCurrentLineNumber()->willReturn($currentLineNumber);
        $commandInvoker->run('remove', $input)->shouldBeCalled();
        $file->setCurrentLineNumber($currentLineNumber)->shouldBeCalled();

        $this->remove($file);
    }

    function it_removes_the_given_line(
        CommandInvoker $commandInvoker,
        File $file
    )
    {
        $lineNumber = 43;
        $input = array(
            'file' => $file,
            'location' => $lineNumber,
        );

        $file->getCurrentLineNumber()->shouldNotBeCalled();
        $commandInvoker->run('remove', $input)->shouldBeCalled();
        $file->setCurrentLineNumber($lineNumber)->shouldBeCalled();

        $this->remove($file, $lineNumber);
    }

    function it_saves_files(Filesystem $filesystem, File $file)
    {
        $filesystem->write($file)->shouldBeCalled();

        $this->save($file);
    }
}
