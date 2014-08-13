<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo;

use Gnugat\Redaktilo\Command\CommandInvoker;
use Gnugat\Redaktilo\Search\PatternNotFoundException;
use Gnugat\Redaktilo\Search\SearchEngine;
use Gnugat\Redaktilo\Service\TextFactory;

/**
 * Provides convenient methods for the following filesystem operations:
 *
 * + opening/creating files
 * + createing text
 * + saving files
 *
 * Provides convenient methods for the following text operations:
 *
 * + looking for given lines and setting the current one to it
 * + inserting given lines above/under the current one or the given one
 * + replacing the current line or the given one
 * + removing the current line or the given one
 *
 * @api
 */
class Editor
{
    /** @var TextFactory */
    private $textFactory;

    /** @var Filesystem */
    private $filesystem;

    /** @var SearchEngine */
    private $searchEngine;

    /** @var CommandInvoker */
    private $commandInvoker;

    /**
     * @param TextFactory    $textFactory
     * @param Filesystem     $filesystem
     * @param SearchEngine   $searchEngine
     * @param CommandInvoker $commandInvoker
     */
    public function __construct(
        TextFactory $textFactory,
        Filesystem $filesystem,
        SearchEngine $searchEngine,
        CommandInvoker $commandInvoker
    )
    {
        $this->textFactory = $textFactory;
        $this->filesystem = $filesystem;
        $this->searchEngine = $searchEngine;
        $this->commandInvoker = $commandInvoker;
    }

    /**
     * Creates an instance of Text from the given string.
     *
     * @param string $string
     *
     * @return Text
     *
     * @api
     */
    public function openText($string)
    {
        return $this->textFactory->make($string);
    }

    /**
     * By default opens existing files only, but can be forced to create new ones.
     *
     * @param string $filename
     * @param bool   $force
     *
     * @return File
     *
     * @throws \Symfony\Component\Filesystem\Exception\FileNotFoundException If the file hasn't be found.
     *
     * @api
     */
    public function openFile($filename, $force = false)
    {
        if (!$this->filesystem->exists($filename) && $force) {
            return $this->filesystem->create($filename);
        }

        return $this->filesystem->open($filename);
    }

    /**
     * File changes are made in memory only, until this methods actually applies
     * them on the filesystem.
     *
     * @param File $file
     *
     * @throws \Symfony\Component\Filesystem\Exception\IOException If the file cannot be written to.
     *
     * @api
     */
    public function saveFile(File $file)
    {
        $this->filesystem->write($file);
    }

    /**
     * Searches the given pattern in the Text above the current line.
     * If the pattern is found, the current line is set to it.
     *
     * @param Text    $text
     * @param mixed   $pattern
     * @param integer $location
     *
     * @throws \Gnugat\Redaktilo\Search\PatternNotFoundException If the pattern hasn't been found
     * @throws \Gnugat\Redaktilo\Search\NotSupportedException    If the given pattern isn't supported by any registered strategy
     *
     * @api
     */
    public function jumpAbove(Text $text, $pattern, $location = null)
    {
        $searchStrategy = $this->searchEngine->resolve($pattern);
        $foundLineNumber = $searchStrategy->findAbove($text, $pattern, $location);
        if (false === $foundLineNumber) {
            throw new PatternNotFoundException($text, $pattern);
        }

        $text->setCurrentLineNumber($foundLineNumber);
    }

    /**
     * Searches the given pattern in the Text under the current line.
     * If the pattern is found, the current line is set to it.
     *
     * @param Text    $text
     * @param mixed   $pattern
     * @param integer $location
     *
     * @throws \Gnugat\Redaktilo\Search\PatternNotFoundException If the pattern hasn't been found
     * @throws \Gnugat\Redaktilo\Search\NotSupportedException    If the given pattern isn't supported by any registered strategy
     *
     * @api
     */
    public function jumpUnder(Text $text, $pattern, $location = null)
    {
        $searchStrategy = $this->searchEngine->resolve($pattern);
        $foundLineNumber = $searchStrategy->findUnder($text, $pattern, $location);
        if (false === $foundLineNumber) {
            throw new PatternNotFoundException($text, $pattern);
        }

        $text->setCurrentLineNumber($foundLineNumber);
    }

    /**
     * @param Text  $text
     * @param mixed $pattern
     *
     * @return bool
     *
     * @throws \Gnugat\Redaktilo\Search\NotSupportedException If the given pattern isn't supported by any registered strategy
     *
     * @api
     */
    public function has(Text $text, $pattern)
    {
        $searchStrategy = $this->searchEngine->resolve($pattern);
        $found = $searchStrategy->findUnder($text, $pattern, 0);

        return (false !== $found);
    }

    /**
     * Inserts the given line above the given line number
     * (or above the current one if none provided).
     * Note: the current line is then set to the new one.
     *
     * @param Text    $text
     * @param string  $addition
     * @param integer $location
     *
     * @api
     */
    public function insertAbove(Text $text, $addition, $location = null)
    {
        $input = array(
            'text' => $text,
            'location' => $location,
            'addition' => $addition,
        );
        $this->commandInvoker->run('insert_above', $input);
    }

    /**
     * Inserts the given addition under the given line number
     * (or under the current one if none provided).
     * Note: the current line is then set to the new one.
     *
     * @param Text    $text
     * @param string  $addition
     * @param integer $location
     *
     * @api
     */
    public function insertUnder(Text $text, $addition, $location = null)
    {
        $input = array(
            'text' => $text,
            'location' => $location,
            'addition' => $addition,
        );
        $this->commandInvoker->run('insert_under', $input);
    }

    /**
     * Replaces the line at the given line number
     * (or at the current one if none provided) with the given replacement.
     *
     * @param Text    $text
     * @param string  $replacement
     * @param integer $location
     *
     * @api
     */
    public function replace(Text $text, $replacement, $location = null)
    {
        $input = array(
            'text' => $text,
            'location' => $location,
            'replacement' => $replacement,
        );
        $this->commandInvoker->run('replace', $input);
    }

    /**
     * Removes the line at the given location
     * (or at the current one if none provided).
     *
     * @param Text    $text
     * @param integer $location
     *
     * @api
     */
    public function remove(Text $text, $location = null)
    {
        $input = array(
            'text' => $text,
            'location' => $location,
        );
        $this->commandInvoker->run('remove', $input);
    }
}
