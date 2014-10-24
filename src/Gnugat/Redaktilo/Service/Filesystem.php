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
    /** @var LineBreak */
    private $lineBreak;

    /** @var SymfonyFilesystem */
    private $symfonyFilesystem;

    /** @var ContentFactory */
    private $contentFactory;

    /**
     * @param LineBreak         $lineBreak
     * @param SymfonyFilesystem $symfonyFilesystem
     * @param ContentFactory    $contentFactory
     */
    public function __construct(
        LineBreak $lineBreak,
        SymfonyFilesystem $symfonyFilesystem,
        ContentFactory $contentFactory = null
    )
    {
        $this->lineBreak = $lineBreak;
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
            $message = sprintf('Failed to open "%s" because it does not exist.', $filename);

            throw new FileNotFoundException($message, 0, null, $filename);
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

            throw new IOException($message, 0, null, $filename);
        }

        return $this->makeFile($filename, '');
    }

    private function makeFile($filename, $content)
    {
        try {
            $lineBreak = $this->lineBreak->detect($content);
        } catch (DifferentLineBreaksFoundException $e) {
            $lineBreak = $e->getNumberLineBreakOther() >= $e->getNumberLineBreakWindows()
                ? LineBreak::LINE_BREAK_OTHER
                : LineBreak::LINE_BREAK_WINDOWS;
        }

        $lines = preg_split('/\R/', $content);

        return new File($filename, $lines, $lineBreak);
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
        $content = $this->contentFactory->make($file);

        $this->symfonyFilesystem->dumpFile($filename, $content, null);
    }
}
