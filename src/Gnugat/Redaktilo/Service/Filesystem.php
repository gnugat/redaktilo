<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Service;

use Gnugat\Redaktilo\File;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

/**
 * Manages actual operations on the filesystem using File as a data source.
 */
class Filesystem
{
    /** @var FileFactory */
    private $FileFactory;

    /** @var SymfonyFilesystem */
    private $symfonyFilesystem;

    /**
     * @param FileFactory       $fileFactory
     * @param SymfonyFilesystem $symfonyFilesystem
     */
    public function __construct(FileFactory $fileFactory, SymfonyFilesystem $symfonyFilesystem)
    {
        $this->fileFactory = $fileFactory;
        $this->symfonyFilesystem = $symfonyFilesystem;
    }

    /**
     * Makes a File out of an existing file.
     *
     * @param string $filename
     *
     * @return File
     *
     * @throws FileNotFoundException
     */
    public function open($filename)
    {
        $content = @file_get_contents($filename);

        if (false === $content) {
            $message = sprintf('Failed to open "%s" because it does not exist.', $filename);

            throw new FileNotFoundException($message, 0, null, $filename);
        }

        return $this->fileFactory->make($filename, $content);
    }

    /**
     * Makes a File out of a new file.
     *
     * @see exists()
     *
     * @param string $filename
     *
     * @return File
     *
     * @throws IOException If the path isn't accessible.
     */
    public function create($filename)
    {
        if ($this->exists($filename)) {
            $message = sprintf('Failed to create "%s" because its path is not accessible.', $filename);

            throw new IOException($message, 0, null, $filename);
        }

        return $this->fileFactory->make($filename, '');
    }

    /**
     * Possible reasons of failure:
     * + path doesn't exists
     * + path isn't accessible
     *
     * @param string $filename
     *
     * @return bool
     */
    public function exists($filename)
    {
        return file_exists($filename);
    }

    /**
     * Atomically writes the given File's content on the actual file.
     *
     * @param File $file
     *
     * @throws IOException If the file cannot be written to.
     */
    public function write(File $file)
    {
        $filename = $file->getFilename();
        $lines = $file->getLines();
        $lineBreak = $file->getLineBreak();
        $content = implode($lineBreak, $lines);

        $this->symfonyFilesystem->dumpFile($filename, $content, null);
    }
}
