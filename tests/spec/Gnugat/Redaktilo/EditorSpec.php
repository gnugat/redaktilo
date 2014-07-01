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

    function it_moves_the_cursor_under_the_current_line(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        File $file
    )
    {
        $pattern = 'No one expects the Spanish inquisition!';
        $foundLineNumber = 42;

        $searchEngine->resolve($pattern)->willReturn($searchStrategy);
        $searchStrategy->findNext($file, $pattern, null)->willReturn($foundLineNumber);
        $file->setCurrentLineNumber($foundLineNumber)->shouldBeCalled();

        $this->jumpDownTo($file, $pattern);

        $searchStrategy->findNext($file, $pattern, null)->willReturn(false);
        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';
        $this->shouldThrow($exception)->duringJumpDownTo($file, $pattern);
    }

    function it_moves_the_cursor_under_the_given_line(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        File $file
    )
    {
        $pattern = 'No one expects the Spanish inquisition!';
        $foundLineNumber = 42;

        $searchEngine->resolve($pattern)->willReturn($searchStrategy);
        $searchStrategy->findNext($file, $pattern, 0)->willReturn($foundLineNumber);
        $file->setCurrentLineNumber($foundLineNumber)->shouldBeCalled();

        $this->jumpDownTo($file, $pattern, 0);

        $searchStrategy->findNext($file, $pattern, 0)->willReturn(false);
        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';
        $this->shouldThrow($exception)->duringJumpDownTo($file, $pattern, 0);
    }

    function it_moves_the_cursor_above_the_current_line(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        File $file
    )
    {
        $pattern = 'Nobody expects the Spanish Inquisition!';
        $foundLineNumber = 4423;

        $searchEngine->resolve($pattern)->willReturn($searchStrategy);
        $searchStrategy->findPrevious($file, $pattern, null)->willReturn($foundLineNumber);
        $file->setCurrentLineNumber($foundLineNumber)->shouldBeCalled();

        $this->jumpUpTo($file, $pattern);

        $searchStrategy->findPrevious($file, $pattern, null)->willReturn(false);
        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';
        $this->shouldThrow($exception)->duringJumpUpTo($file, $pattern);
    }

    function it_moves_the_cursor_above_the_given_line(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        File $file
    )
    {
        $pattern = 'Nobody expects the Spanish Inquisition!';
        $foundLineNumber = 4423;

        $searchEngine->resolve($pattern)->willReturn($searchStrategy);
        $searchStrategy->findPrevious($file, $pattern, 0)->willReturn($foundLineNumber);
        $file->setCurrentLineNumber($foundLineNumber)->shouldBeCalled();

        $this->jumpUpTo($file, $pattern, 0);

        $searchStrategy->findPrevious($file, $pattern, 0)->willReturn(false);
        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';
        $this->shouldThrow($exception)->duringJumpUpTo($file, $pattern, 0);
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

    function it_inserts_lines_above_the_current_one(
        CommandInvoker $commandInvoker,
        File $file
    )
    {
        $addition = 'We are the knights who say Ni!';
        $input = array(
            'file' => $file,
            'location' => null,
            'addition' => $addition,
        );

        $commandInvoker->run('insert_above', $input)->shouldBeCalled();

        $this->addBefore($file, $addition);
    }

    function it_inserts_lines_above_the_given_one(
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

        $commandInvoker->run('insert_above', $input)->shouldBeCalled();

        $this->addBefore($file, $addition, $lineNumber);
    }

    function it_inserts_lines_under_the_current_one(
        CommandInvoker $commandInvoker,
        File $file
    )
    {
        $addition = 'We are the knights who say Ni!';
        $input = array(
            'file' => $file,
            'location' => null,
            'addition' => $addition,
        );

        $commandInvoker->run('insert_under', $input)->shouldBeCalled();

        $this->addAfter($file, $addition);
    }

    function it_inserts_lines_under_the_given_one(
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

        $commandInvoker->run('insert_under', $input)->shouldBeCalled();

        $this->addAfter($file, $addition, $lineNumber);
    }

    function it_replaces_the_current_line(
        CommandInvoker $commandInvoker,
        File $file
    )
    {
        $replacement = 'We are knights who say Ni!';
        $input = array(
            'file' => $file,
            'location' => null,
            'replacement' => $replacement,
        );

        $commandInvoker->run('replace', $input)->shouldBeCalled();

        $this->changeTo($file, $replacement);
    }

    function it_replaces_the_given_line(
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

        $commandInvoker->run('replace', $input)->shouldBeCalled();

        $this->changeTo($file, $replacement, $lineNumber);
    }

    function it_removes_the_current_line(
        CommandInvoker $commandInvoker,
        File $file
    )
    {
        $input = array(
            'file' => $file,
            'location' => null,
        );

        $commandInvoker->run('remove', $input)->shouldBeCalled();

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

        $commandInvoker->run('remove', $input)->shouldBeCalled();

        $this->remove($file, $lineNumber);
    }

    function it_saves_files(Filesystem $filesystem, File $file)
    {
        $filesystem->write($file)->shouldBeCalled();

        $this->save($file);
    }
}
