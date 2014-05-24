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

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

/**
 * Manages actual operations on the filesystem using File as a data source.
 *
 * @api
 */
class Filesystem
{
    /** @var SymfonyFilesystem */
    private $symfonyFilesystem;

    /** @param SymfonyFilesystem $symfonyFilesystem */
    public function __construct(SymfonyFilesystem $symfonyFilesystem)
    {
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
     *
     * @api
     */
    public function open($filename)
    {
        $content = @file_get_contents($filename);

        if (false === $content) {
            $message = sprintf('Failed to open "%s" because it does not exist.', $filename);

            throw new FileNotFoundException($message, 0, null, $filename);
        }

        return new File($filename, $content);
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
     *
     * @api
     */
    public function create($filename)
    {
        if ($this->exists($filename)) {
            $message = sprintf('Failed to create "%s" because its path is not accessible.', $filename);

            throw new IOException($message, 0, null, $filename);
        }

        return new File($filename, '');
    }

    /**
     * Possible reasons of failure:
     * + path doesn't exists
     * + path isn't accessible
     *
     * @param string $filename
     *
     * @return bool
     *
     * @api
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
     *
     * @api
     */
    public function write(File $file)
    {
        $filename = $file->getFilename();
        $content = $file->read();

        $this->symfonyFilesystem->dumpFile($filename, $content, null);
    }
}
