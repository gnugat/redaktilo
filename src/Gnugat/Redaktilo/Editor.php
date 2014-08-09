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

/**
 * Provides convenient methods for the following filesystem operations:
 *
 * + opening/creating files
 * + saving files
 *
 * Provides convenient methods for the following file operations:
 *
 * + looking for given lines and setting the current one to it
 * + inserting given lines above/under the current one or the given one
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
    public function open($filename, $force = false)
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
    public function save(File $file)
    {
        $this->filesystem->write($file);
    }

    /**
     * Searches the given pattern in the File above the current line.
     * If the pattern is found, the current line is set to it.
     *
     * @param File    $file
     * @param mixed   $pattern
     * @param integer $location
     *
     * @throws \Gnugat\Redaktilo\Search\PatternNotFoundException If the pattern hasn't been found
     * @throws \Gnugat\Redaktilo\Search\NotSupportedException    If the given pattern isn't supported by any registered strategy
     *
     * @api
     */
    public function jumpAbove(File $file, $pattern, $location = null)
    {
        $searchStrategy = $this->searchEngine->resolve($pattern);
        $foundLineNumber = $searchStrategy->findAbove($file, $pattern, $location);
        if (false === $foundLineNumber) {
            throw new PatternNotFoundException($file, $pattern);
        }

        $file->setCurrentLineNumber($foundLineNumber);
    }

    /**
     * Searches the given pattern in the File under the current line.
     * If the pattern is found, the current line is set to it.
     *
     * @param File    $file
     * @param mixed   $pattern
     * @param integer $location
     *
     * @throws \Gnugat\Redaktilo\Search\PatternNotFoundException If the pattern hasn't been found
     * @throws \Gnugat\Redaktilo\Search\NotSupportedException    If the given pattern isn't supported by any registered strategy
     *
     * @api
     */
    public function jumpUnder(File $file, $pattern, $location = null)
    {
        $searchStrategy = $this->searchEngine->resolve($pattern);
        $foundLineNumber = $searchStrategy->findUnder($file, $pattern, $location);
        if (false === $foundLineNumber) {
            throw new PatternNotFoundException($file, $pattern);
        }

        $file->setCurrentLineNumber($foundLineNumber);
    }

    /**
     * @param File  $file
     * @param mixed $pattern
     *
     * @return bool
     *
     * @throws \Gnugat\Redaktilo\Search\NotSupportedException If the given pattern isn't supported by any registered strategy
     *
     * @api
     */
    public function has(File $file, $pattern)
    {
        $searchStrategy = $this->searchEngine->resolve($pattern);
        $found = $searchStrategy->findUnder($file, $pattern, 0);

        return (false !== $found);
    }

    /**
     * Inserts the given line above the given line number
     * (or above the current one if none provided).
     * Note: the current line is then set to the new one.
     *
     * @param File    $file
     * @param string  $addition
     * @param integer $location
     *
     * @api
     */
    public function insertAbove(File $file, $addition, $location = null)
    {
        $input = array(
            'file' => $file,
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
     * @param File    $file
     * @param string  $addition
     * @param integer $location
     *
     * @api
     */
    public function insertUnder(File $file, $addition, $location = null)
    {
        $input = array(
            'file' => $file,
            'location' => $location,
            'addition' => $addition,
        );
        $this->commandInvoker->run('insert_under', $input);
    }

    /**
     * Replaces the line at the given line number
     * (or at the current one if none provided) with the given replacement.
     *
     * @param File    $file
     * @param string  $replacement
     * @param integer $location
     *
     * @api
     */
    public function replace(File $file, $replacement, $location = null)
    {
        $input = array(
            'file' => $file,
            'location' => $location,
            'replacement' => $replacement,
        );
        $this->commandInvoker->run('replace', $input);
    }

    /**
     * Removes the line at the given location
     * (or at the current one if none provided).
     *
     * @param File    $file
     * @param integer $location
     *
     * @api
     */
    public function remove(File $file, $location = null)
    {
        $input = array(
            'file' => $file,
            'location' => $location,
        );
        $this->commandInvoker->run('remove', $input);
    }
}
