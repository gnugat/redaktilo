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

use Gnugat\Redaktilo\File\Filesystem;

/**
 * Allows File manipulations:
 *
 * + open an existing file
 * + move the cursor to the desired area
 * + insert whatever you want around the cursor
 * + save your modifications
 *
 * Generally delegates read and write operations to Filesystem.
 */
class Editor
{
    /** @var Filesystem */
    private $filesystem;

    /** @var LineFile */
    private $file;

    /** @param Filesystem $filesystem */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Opens an existing file.
     *
     * @param string $filename
     */
    public function open($filename)
    {
        $this->file = $this->filesystem->read($filename, Filesystem::LINE_FILE_TYPE);
    }

    /**
     * Moves down the cursor to the given line.
     *
     * @param string $pattern
     */
    public function jumpDownTo($pattern)
    {
        $lines = $this->file->getLines();
        $filename = $this->file->getFilename();
        $currentLineNumber = $this->file->getCurrentLineNumber() + 1;
        $length = count($lines);
        while ($currentLineNumber < $length) {
            if ($lines[$currentLineNumber] === $pattern) {
                $this->file->setCurrentLineNumber($currentLineNumber);

                return;
            }
            $currentLineNumber++;
        }

        throw new \Exception("Couldn't find line $pattern in $filename");
    }

    /**
     * Moves up the cursor to the given line.
     *
     * @param string $pattern
     */
    public function jumpUpTo($pattern)
    {
        $lines = $this->file->getLines();
        $filename = $this->file->getFilename();
        $currentLineNumber = $this->file->getCurrentLineNumber() - 1;
        while (0 <= $currentLineNumber) {
            if ($lines[$currentLineNumber] === $pattern) {
                $this->file->setCurrentLineNumber($currentLineNumber);

                return;
            }
            $currentLineNumber--;
        }

        throw new \Exception("Couldn't find line $pattern in $filename");
    }

    /**
     * Moves up the cursor and inserts the given line.
     *
     * @param string $add
     */
    public function addBefore($add)
    {
        $currentLineNumber = $this->file->getCurrentLineNumber();
        $preEditLines = $this->file->getLines();
        $postEditLines = array();
        foreach ($preEditLines as $lineNumber => $line) {
            if ($currentLineNumber === $lineNumber) {
                $postEditLines[] = $add;
            }
            $postEditLines[] = $line;
        }
        $this->file->write($postEditLines);
    }

    /**
     * Moves down the cursor and inserts the given line.
     *
     * @param string $add
     */
    public function addAfter($add)
    {
        $currentLineNumber = $this->file->getCurrentLineNumber();
        $currentLineNumber++;
        $this->file->setCurrentLineNumber($currentLineNumber);
        $preEditLines = $this->file->getLines();
        $postEditLines = array();
        foreach ($preEditLines as $lineNumber => $line) {
            $postEditLines[] = $line;
            if ($currentLineNumber === $lineNumber) {
                $postEditLines[] = $add;
            }
        }
        $this->file->write($postEditLines);
    }

    /**
     * Backups the modifications.
     */
    public function save()
    {
        $this->filesystem->write($this->file);
    }
}
