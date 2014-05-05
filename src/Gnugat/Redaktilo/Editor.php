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
use Gnugat\Redaktilo\File;

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

    /** @var integer */
    private $cursor = 0;

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
        $this->cursor = 0;
    }

    /**
     * Moves down the cursor to the given line.
     *
     * @param string $pattern
     */
    public function jumpDownTo($pattern)
    {
        $lines = $this->file->read();
        $filename = $this->file->getFilename();
        $length = count($lines);
        for ($line = $this->cursor + 1; $line < $length; $line++) {
            if ($lines[$line] === $pattern) {
                $this->cursor = $line;

                return;
            }
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
        $lines = $this->file->read();
        $filename = $this->file->getFilename();
        for ($line = $this->cursor - 1; $line >= 0; $line--) {
            if ($lines[$line] === $pattern) {
                $this->cursor = $line;

                return;
            }
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
        $cursor = $this->cursor--;
        $preEditLines = $this->file->read();
        $postEditLines = array();
        foreach ($preEditLines as $lineNumber => $line) {
            if ($cursor === $lineNumber) {
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
        $cursor = $this->cursor++;
        $preEditLines = $this->file->read();
        $postEditLines = array();
        foreach ($preEditLines as $lineNumber => $line) {
            $postEditLines[] = $line;
            if ($cursor === $lineNumber) {
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
