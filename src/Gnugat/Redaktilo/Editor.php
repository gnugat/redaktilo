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

    /** @param Filesystem $filesystem */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * By default opens existing files only, but can be forced to create new ones.
     *
     * @param string $filename
     * @param bool   $force
     *
     * @return File
     *
     * @throws Symfony\Component\Filesystem\Exception\FileNotFoundException
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
     * @api
     */
    public function save(File $file)
    {
        $this->filesystem->write($file);
    }

    /**
     * Searches the given line in the file after the current one.
     * If the line is found, the current one is set to it.
     *
     * @param File   $file
     * @param string $pattern
     *
     * @throws \Exception If the line couldn't be found in the file
     *
     * @api
     */
    public function jumpDownTo(File $file, $pattern)
    {
        $lines = $file->readlines();
        $filename = $file->getFilename();
        $currentLineNumber = $file->getCurrentLineNumber() + 1;
        $length = count($lines) - $currentLineNumber;
        while ($currentLineNumber < $length) {
            if ($lines[$currentLineNumber] === $pattern) {
                $file->setCurrentLineNumber($currentLineNumber);

                return;
            }
            $currentLineNumber++;
        }

        throw new \Exception("Couldn't find line $pattern in $filename");
    }

    /**
     * Searches the given line in the file before the current one.
     * If the line is found, the current one is set to it.
     *
     * @param File   $file
     * @param string $pattern
     *
     * @throws \Exception If the line couldn't be found in the file
     *
     * @api
     */
    public function jumpUpTo(File $file, $pattern)
    {
        $lines = $file->readlines();
        $filename = $file->getFilename();
        $currentLineNumber = $file->getCurrentLineNumber() - 1;
        while (0 <= $currentLineNumber) {
            if ($lines[$currentLineNumber] === $pattern) {
                $file->setCurrentLineNumber($currentLineNumber);

                return;
            }
            $currentLineNumber--;
        }

        throw new \Exception("Couldn't find line $pattern in $filename");
    }

    /**
     * @param File   $file
     * @param string $pattern
     *
     * @return bool
     */
    public function has(File $file, $pattern)
    {
        return $file->hasLine($pattern);
    }

    /**
     * Inserts the given line before the current one.
     * Note: the current line is then set to the new one.
     *
     * @param File   $file
     * @param string $add
     *
     * @api
     */
    public function addBefore(File $file, $add)
    {
        $currentLineNumber = $file->getCurrentLineNumber();

        $file->insertLineAt($add, $currentLineNumber);
    }

    /**
     * Inserts the given line after the current one.
     * Note: the current line is then set to the new one.
     *
     * @param File   $file
     * @param string $add
     *
     * @api
     */
    public function addAfter(File $file, $add)
    {
        $currentLineNumber = $file->getCurrentLineNumber();
        $currentLineNumber++;
        $file->setCurrentLineNumber($currentLineNumber);

        $file->insertLineAt($add, $currentLineNumber);
    }

    /**
     * Changes the current line to the given line.
     *
     * @param File   $file
     * @param string $line
     *
     * @api
     */
    public function changeTo(File $file, $line)
    {
        $currentLineNumber = $file->getCurrentLineNumber();

        $file->changeLineTo($line, $currentLineNumber);
    }

    /**
     * Replaces the current line using a regex and replace string/callback.
     *
     * @param File            $file
     * @param string          $regex
     * @param string|callable $callback
     *
     * @api
     */
    public function replaceWith(File $file, $regex, $replace)
    {
        $currentLineNumber = $file->getCurrentLineNumber();
        $lines = $file->readlines();
        $line = $lines[$currentLineNumber];

        if (is_callable($replace)) {
            $line = preg_replace_callback($regex, $replace, $line);
        } elseif (is_string($replace)) {
            $line = preg_replace($regex, $replace, $line);
        } else {
            throw new \InvalidArgumentException(sprintf('Expected a callable or valid regex as third argument to Edit#replaceWith(), got "%s".', $replace));
        }

        $file->changeLineTo($line, $currentLineNumber);
    }

    /** @param File $file */
    public function remove(File $file)
    {
        $currentLineNumber = $file->getCurrentLineNumber();

        $file->removeLine($currentLineNumber);
    }
}
