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

    /** @param Filesystem $filesystem */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Opens an existing file.
     *
     * @param string $filename
     *
     * @return File
     */
    public function open($filename)
    {
        return $this->filesystem->read($filename, Filesystem::LINE_FILE_TYPE);
    }

    /**
     * Moves down the cursor to the given line.
     *
     * @param File   $file
     * @param string $pattern
     */
    public function jumpDownTo(File $file, $pattern)
    {
        $lines = $file->getLines();
        $filename = $file->getFilename();
        $currentLineNumber = $file->getCurrentLineNumber() + 1;
        $length = count($lines);
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
     * Moves up the cursor to the given line.
     *
     * @param File   $file
     * @param string $pattern
     */
    public function jumpUpTo(File $file, $pattern)
    {
        $lines = $file->getLines();
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
     * Moves up the cursor and inserts the given line.
     *
     * @param File   $file
     * @param string $add
     */
    public function addBefore(File $file, $add)
    {
        $currentLineNumber = $file->getCurrentLineNumber();
        $preEditLines = $file->getLines();
        $postEditLines = array();
        foreach ($preEditLines as $lineNumber => $line) {
            if ($currentLineNumber === $lineNumber) {
                $postEditLines[] = $add;
            }
            $postEditLines[] = $line;
        }
        $file->write($postEditLines);
    }

    /**
     * Moves down the cursor and inserts the given line.
     *
     * @param File   $file
     * @param string $add
     */
    public function addAfter(File $file, $add)
    {
        $currentLineNumber = $file->getCurrentLineNumber();
        $currentLineNumber++;
        $file->setCurrentLineNumber($currentLineNumber);
        $preEditLines = $file->getLines();
        $postEditLines = array();
        foreach ($preEditLines as $lineNumber => $line) {
            $postEditLines[] = $line;
            if ($currentLineNumber === $lineNumber) {
                $postEditLines[] = $add;
            }
        }
        $file->write($postEditLines);
    }

    /**
     * Backups the modifications.
     *
     * @param File $file
     */
    public function save(File $file)
    {
        $this->filesystem->write($file);
    }
}
