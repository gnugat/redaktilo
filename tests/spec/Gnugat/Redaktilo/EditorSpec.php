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
use Gnugat\Redaktilo\Search\SearchEngine;
use Gnugat\Redaktilo\Search\SearchStrategy;
use Gnugat\Redaktilo\Service\Filesystem;
use Gnugat\Redaktilo\Service\TextFactory;
use Gnugat\Redaktilo\Text;
use PhpSpec\ObjectBehavior;

class EditorSpec extends ObjectBehavior
{
    const FILENAME = '/tmp/file-to-edit.txt';

    function let(
        File $file,
        TextFactory $textFactory,
        Filesystem $filesystem,
        SearchEngine $searchEngine,
        CommandInvoker $commandInvoker
    )
    {
        $file->getFilename()->willReturn(self::FILENAME);

        $this->beConstructedWith(
            $textFactory,
            $filesystem,
            $searchEngine,
            $commandInvoker
        );
    }

    function it_opens_text(TextFactory $textFactory, Text $text)
    {
        $string = "Waitress: Well, there's spam egg sausage and spam, that's not got much spam in it.";

        $textFactory->make($string)->willReturn($text);

        $this->openText($string);
    }

    function it_opens_existing_files(Filesystem $filesystem, File $file)
    {
        $filesystem->exists(self::FILENAME)->willReturn(true);
        $filesystem->open(self::FILENAME)->willReturn($file);

        $this->openFile(self::FILENAME);
    }

    function it_cannot_open_new_files(Filesystem $filesystem, File $file)
    {
        $exception = 'Symfony\Component\Filesystem\Exception\FileNotFoundException';

        $filesystem->exists(self::FILENAME)->willReturn(false);
        $filesystem->open(self::FILENAME)->willThrow($exception);

        $this->shouldThrow($exception)->duringOpenFile(self::FILENAME);
    }

    function it_creates_new_files(Filesystem $filesystem, File $file)
    {
        $filesystem->exists(self::FILENAME)->willReturn(false);
        $filesystem->create(self::FILENAME)->willReturn($file);

        $this->openFile(self::FILENAME, true);
    }

    function it_moves_the_cursor_above_the_current_line(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        Text $text
    )
    {
        $pattern = 'Nobody expects the Spanish Inquisition!';
        $foundLineNumber = 4423;

        $searchEngine->resolve($pattern)->willReturn($searchStrategy);
        $searchStrategy->findAbove($text, $pattern, null)->willReturn($foundLineNumber);
        $text->setCurrentLineNumber($foundLineNumber)->shouldBeCalled();

        $this->jumpAbove($text, $pattern);

        $searchStrategy->findAbove($text, $pattern, null)->willReturn(false);
        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';
        $this->shouldThrow($exception)->duringJumpAbove($text, $pattern);
    }

    function it_moves_the_cursor_above_the_given_line(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        Text $text
    )
    {
        $pattern = 'Nobody expects the Spanish Inquisition!';
        $foundLineNumber = 4423;

        $searchEngine->resolve($pattern)->willReturn($searchStrategy);
        $searchStrategy->findAbove($text, $pattern, 0)->willReturn($foundLineNumber);
        $text->setCurrentLineNumber($foundLineNumber)->shouldBeCalled();

        $this->jumpAbove($text, $pattern, 0);

        $searchStrategy->findAbove($text, $pattern, 0)->willReturn(false);
        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';
        $this->shouldThrow($exception)->duringJumpAbove($text, $pattern, 0);
    }

    function it_moves_the_cursor_under_the_current_line(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        Text $text
    )
    {
        $pattern = 'No one expects the Spanish inquisition!';
        $foundLineNumber = 42;

        $searchEngine->resolve($pattern)->willReturn($searchStrategy);
        $searchStrategy->findUnder($text, $pattern, null)->willReturn($foundLineNumber);
        $text->setCurrentLineNumber($foundLineNumber)->shouldBeCalled();

        $this->jumpUnder($text, $pattern);

        $searchStrategy->findUnder($text, $pattern, null)->willReturn(false);
        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';
        $this->shouldThrow($exception)->duringJumpUnder($text, $pattern);
    }

    function it_moves_the_cursor_under_the_given_line(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        Text $text
    )
    {
        $pattern = 'No one expects the Spanish inquisition!';
        $foundLineNumber = 42;

        $searchEngine->resolve($pattern)->willReturn($searchStrategy);
        $searchStrategy->findUnder($text, $pattern, 0)->willReturn($foundLineNumber);
        $text->setCurrentLineNumber($foundLineNumber)->shouldBeCalled();

        $this->jumpUnder($text, $pattern, 0);

        $searchStrategy->findUnder($text, $pattern, 0)->willReturn(false);
        $exception = 'Gnugat\Redaktilo\Search\PatternNotFoundException';
        $this->shouldThrow($exception)->duringJumpUnder($text, $pattern, 0);
    }

    function it_checks_pattern_existence(
        SearchEngine $searchEngine,
        SearchStrategy $searchStrategy,
        Text $text
    )
    {
        $pattern = 'No one expects the spanish inquisition!';

        $searchEngine->resolve($pattern)->willReturn($searchStrategy);
        $searchStrategy->findUnder($text, $pattern, 0)->willReturn(42);

        $this->has($text, $pattern)->shouldBe(true);
    }

    function it_inserts_lines_above_the_current_one(
        CommandInvoker $commandInvoker,
        Text $text
    )
    {
        $addition = 'We are the knights who say Ni!';
        $input = array(
            'text' => $text,
            'location' => null,
            'addition' => $addition,
        );

        $commandInvoker->run('insert_above', $input)->shouldBeCalled();

        $this->insertAbove($text, $addition);
    }

    function it_inserts_lines_above_the_given_one(
        CommandInvoker $commandInvoker,
        Text $text
    )
    {
        $lineNumber = 43;
        $addition = 'We are the knights who say Ni!';
        $input = array(
            'text' => $text,
            'location' => $lineNumber,
            'addition' => $addition,
        );

        $commandInvoker->run('insert_above', $input)->shouldBeCalled();

        $this->insertAbove($text, $addition, $lineNumber);
    }

    function it_inserts_lines_under_the_current_one(
        CommandInvoker $commandInvoker,
        Text $text
    )
    {
        $addition = 'We are the knights who say Ni!';
        $input = array(
            'text' => $text,
            'location' => null,
            'addition' => $addition,
        );

        $commandInvoker->run('insert_under', $input)->shouldBeCalled();

        $this->insertUnder($text, $addition);
    }

    function it_inserts_lines_under_the_given_one(
        CommandInvoker $commandInvoker,
        Text $text
    )
    {
        $lineNumber = 43;
        $addition = 'We are the knights who say Ni!';
        $input = array(
            'text' => $text,
            'location' => $lineNumber,
            'addition' => $addition,
        );

        $commandInvoker->run('insert_under', $input)->shouldBeCalled();

        $this->insertUnder($text, $addition, $lineNumber);
    }

    function it_replaces_the_current_line(
        CommandInvoker $commandInvoker,
        Text $text
    )
    {
        $replacement = 'We are knights who say Ni!';
        $input = array(
            'text' => $text,
            'location' => null,
            'replacement' => $replacement,
        );

        $commandInvoker->run('replace', $input)->shouldBeCalled();

        $this->replace($text, $replacement);
    }

    function it_replaces_the_given_line(
        CommandInvoker $commandInvoker,
        Text $text
    )
    {
        $lineNumber = 43;
        $replacement = 'We are knights who say Ni!';
        $input = array(
            'text' => $text,
            'location' => $lineNumber,
            'replacement' => $replacement,
        );

        $commandInvoker->run('replace', $input)->shouldBeCalled();

        $this->replace($text, $replacement, $lineNumber);
    }

    function it_removes_the_current_line(
        CommandInvoker $commandInvoker,
        Text $text
    )
    {
        $input = array(
            'text' => $text,
            'location' => null,
        );

        $commandInvoker->run('remove', $input)->shouldBeCalled();

        $this->remove($text);
    }

    function it_removes_the_given_line(
        CommandInvoker $commandInvoker,
        Text $text
    )
    {
        $lineNumber = 43;
        $input = array(
            'text' => $text,
            'location' => $lineNumber,
        );

        $commandInvoker->run('remove', $input)->shouldBeCalled();

        $this->remove($text, $lineNumber);
    }

    function it_saves_files(Filesystem $filesystem, File $file)
    {
        $filesystem->write($file)->shouldBeCalled();

        $this->saveFile($file);
    }
}
