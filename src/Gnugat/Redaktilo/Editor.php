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

use Gnugat\Redaktilo\Filesystem\Filesystem;

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
     * @param bool   $force
     *
     * @return File
     *
     * @throws Symfony\Component\Filesystem\Exception\FileNotFoundException
     */
    public function open($filename, $force = false)
    {
        if (!$this->filesystem->exists($filename) && $force) {
            return $this->filesystem->create($filename);
        }

        return $this->filesystem->open($filename);
    }

    /**
     * Moves down the cursor to the given line.
     *
     * @param File   $file
     * @param string $pattern
     */
    public function jumpDownTo(File $file, $pattern)
    {
        $lines = $file->readlines();
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
     * Moves up the cursor and inserts the given line.
     *
     * @param File   $file
     * @param string $add
     */
    public function addBefore(File $file, $add)
    {
        $currentLineNumber = $file->getCurrentLineNumber();

        $file->insertLineAt($add, $currentLineNumber);
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

        $file->insertLineAt($add, $currentLineNumber);
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
