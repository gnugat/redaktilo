<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Service;

use Gnugat\Redaktilo\Exception\FileNotFoundException;
use Gnugat\Redaktilo\Exception\IOException;
use Gnugat\Redaktilo\Exception\NoFilenameGivenException;
use Gnugat\Redaktilo\File;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Symfony\Component\Filesystem\Exception\IOException as SymfonyIOException;

/**
 * Manages actual operations on the filesystem using File as a data source.
 */
class Filesystem
{
    /** @var SymfonyFilesystem */
    private $symfonyFilesystem;

    /** @var ContentFactory */
    private $contentFactory;

    /**
     * @param SymfonyFilesystem $symfonyFilesystem
     * @param ContentFactory    $contentFactory
     */
    public function __construct(
        SymfonyFilesystem $symfonyFilesystem,
        ContentFactory $contentFactory = null
    ) {
        $this->symfonyFilesystem = $symfonyFilesystem;
        // @deprecated 1.3 ContentFactory becomes mandatory
        $this->contentFactory = $contentFactory ?: new ContentFactory();
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
        if (!$this->exists($filename) || false === $content = file_get_contents($filename)) {
            throw new FileNotFoundException($filename);
        }

        return $this->makeFile($filename, $content);
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

            throw new IOException($filename, $message);
        }

        return $this->makeFile($filename, '');
    }

    private function makeFile($filename, $content)
    {
        $file = File::fromString($content);
        $file->setFilename($filename);

        return $file;
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
        if (null === $filename) {
            throw new NoFilenameGivenException();
        }
        $content = $this->contentFactory->make($file);

        try {
            $this->symfonyFilesystem->dumpFile($filename, $content, null);
        } catch (SymfonyIOException $e) {
            $message = sprintf('Failed to write "%s".', $filename);

            throw new IOException($filename, $message, $e);
        }
    }
}
