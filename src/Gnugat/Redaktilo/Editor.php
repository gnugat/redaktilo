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
 * + inserting given lines before/after the current one
 *
 * @api
 */
class Editor
{
    /** @var Filesystem */
    private $filesystem;

    /** @var SearchEngine */
    private $searchEngine;

    /** @var CommandInvoker */
    private $commandInvoker;

    /**
     * @param Filesystem     $filesystem
     * @param SearchEngine   $searchEngine
     * @param CommandInvoker $commandInvoker
     */
    public function __construct(
        Filesystem $filesystem,
        SearchEngine $searchEngine,
        CommandInvoker $commandInvoker
    )
    {
        $this->filesystem = $filesystem;
        $this->searchEngine = $searchEngine;
        $this->commandInvoker = $commandInvoker;
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
     * Searches the given pattern in the File after the current line.
     * If the pattern is found, the current line is set to it.
     *
     * @param File    $file
     * @param mixed   $pattern
     * @param integer $after
     *
     * @throws \Gnugat\Redaktilo\Search\PatternNotFoundException If the pattern hasn't been found
     * @throws \Gnugat\Redaktilo\Search\NotSupportedException    If the given pattern isn't supported by any registered strategy
     *
     * @api
     */
    public function jumpDownTo(File $file, $pattern, $after = null)
    {
        $searchStrategy = $this->searchEngine->resolve($pattern);
        $foundLineNumber = $searchStrategy->findNext($file, $pattern, $after);
        if (false === $foundLineNumber) {
            throw new PatternNotFoundException($file, $pattern);
        }

        $file->setCurrentLineNumber($foundLineNumber);
    }

    /**
     * Searches the given pattern in the File before the current line.
     * If the pattern is found, the current line is set to it.
     *
     * @param File    $file
     * @param mixed   $pattern
     * @param integer $before
     *
     * @throws \Gnugat\Redaktilo\Search\PatternNotFoundException If the pattern hasn't been found
     * @throws \Gnugat\Redaktilo\Search\NotSupportedException    If the given pattern isn't supported by any registered strategy
     *
     * @api
     */
    public function jumpUpTo(File $file, $pattern, $before = null)
    {
        $searchStrategy = $this->searchEngine->resolve($pattern);
        $foundLineNumber = $searchStrategy->findPrevious($file, $pattern, $before);
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
        $found = $searchStrategy->findNext($file, $pattern, 0);

        return (false !== $found);
    }

    /**
     * Inserts the given line before the current one.
     * Note: the current line is then set to the new one.
     *
     * @param File     $file
     * @param string   $addition
     * @param int|null $location
     *
     * @throws \Gnugat\Redaktilo\Search\NotSupportedException If the current line number isn't supported by any registered strategy
     *
     * @api
     */
    public function addBefore(File $file, $addition, $location=null)
    {
        if ($location === null || !is_integer($location)) {
            $location = $file->getCurrentLineNumber();
        }

        $input = array(
            'file' => $file,
            'location' => $location,
            'addition' => $addition,
        );
        $this->commandInvoker->run('insert', $input);

        $file->setCurrentLineNumber($location);
    }

    /**
     * Inserts the given line after the current one.
     * Note: the current line is then set to the new one.
     *
     * @param File     $file
     * @param string   $addition
     * @param int|null $location
     *
     * @throws \Gnugat\Redaktilo\Search\NotSupportedException If the current line number isn't supported by any registered strategy
     *
     * @api
     */
    public function addAfter(File $file, $addition, $location=null)
    {
        if ($location === null || !is_integer($location)) {
            $location = $file->getCurrentLineNumber();
        }
        $location++;

        $input = array(
            'file' => $file,
            'location' => $location,
            'addition' => $addition,
        );
        $this->commandInvoker->run('insert', $input);

        $file->setCurrentLineNumber($location);
    }

    /**
     * Changes the current line to the given line.
     *
     * @param File     $file
     * @param string   $replacement
     * @param int|null $location
     *
     * @throws \Gnugat\Redaktilo\Search\NotSupportedException If the current line number isn't supported by any registered strategy
     *
     * @api
     */
    public function changeTo(File $file, $replacement, $location=null)
    {
        if ($location === null || !is_integer($location)) {
            $location = $file->getCurrentLineNumber();
        }

        $input = array(
            'file' => $file,
            'location' => $location,
            'replacement' => $replacement,
        );
        $this->commandInvoker->run('replace', $input);

        $file->setCurrentLineNumber($location);
    }

    /**
     * Replaces the current line using a regex and replace string/callback.
     *
     * @param File            $file
     * @param string          $regex
     * @param string|callable $replace
     * @param int|null        $location
     *
     * @throws \InvalidArgumentException If $replace is not a valid callable or regex
     *
     * @api
     */
    public function replaceWith(File $file, $regex, $replace, $location=null)
    {
        if ($location === null || !is_integer($location)) {
            $location = $file->getCurrentLineNumber();
        }

        $converter = new \Gnugat\Redaktilo\Converter\LineContentConverter();
        $lines = $converter->from($file);
        $line = $lines[$location];

        if (is_callable($replace)) {
            $line = preg_replace_callback($regex, $replace, $line);
        } elseif (is_string($replace)) {
            $line = preg_replace($regex, $replace, $line);
        } else {
            throw new \InvalidArgumentException(sprintf('Expected a callable or valid regex as third argument to Edit#replaceWith(), got "%s".', $replace));
        }
        $file->changeLineTo($line, $location);

        $file->setCurrentLineNumber($location);
    }

    /**
     * @param File     $file
     * @param int|null $location
     *
     * @api
     */
    public function remove(File $file, $location=null)
    {
        if ($location === null || !is_integer($location)) {
            $location = $file->getCurrentLineNumber();
        }

        $input = array(
            'file' => $file,
            'location' => $location,
        );
        $this->commandInvoker->run('remove', $input);

        $file->setCurrentLineNumber($location);
    }
}
