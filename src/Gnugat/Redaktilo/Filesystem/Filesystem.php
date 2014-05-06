<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Filesystem;

use Gnugat\Redaktilo\File;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

/**
 * Manages actual operations on the filesystem using File as a data source.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class Filesystem
{
    const LINE_BREAK_OTHER = "\n";
    const LINE_BREAK_WINDOWS = "\r\n";

    /** @var SymfonyFilesystem */
    private $symfonyFilesystem;

    /** @param SymfonyFilesystem $symfonyFilesystem */
    public function __construct(SymfonyFilesystem $symfonyFilesystem)
    {
        $this->symfonyFilesystem = $symfonyFilesystem;
    }

    /**
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

        if (false === strpos($content, self::LINE_BREAK_OTHER)) {
            $newLineCharacter = PHP_EOL;
        } elseif (false !== strpos($content, self::LINE_BREAK_WINDOWS)) {
            $newLineCharacter = self::LINE_BREAK_WINDOWS;
        } else {
            $newLineCharacter = self::LINE_BREAK_OTHER;
        }

        return new File($filename, $content, $newLineCharacter);
    }

    /**
     * @param string $filename
     *
     * @return File
     *
     * @throws IOException If the file already exists
     */
    public function create($filename)
    {
        if ($this->exists($filename)) {
            $message = sprintf('Failed to create "%s" because it already exists.', $filename);

            throw new IOException($message, 0, null, $filename);
        }

        return new File($filename, '');
    }

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function exists($filename)
    {
        return file_exists($filename);
    }

    /** @param File $file */
    public function write(File $file)
    {
        $filename = $file->getFilename();
        $content = $file->getContent();

        $this->symfonyFilesystem->dumpFile($filename, $content, null);
    }
}
