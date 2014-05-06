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

/**
 * Manages read and write operations using File as a data source.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class Filesystem
{
    const LINE_BREAK_OTHER = "\n";
    const LINE_BREAK_WINDOWS = "\r\n";

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
     */
    public function create($filename)
    {
        return new File($filename, '');
    }

    /** @param File $file */
    public function write(File $file)
    {
        file_put_contents($file->getFilename(), $file->getContent());
    }
}
