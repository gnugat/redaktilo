<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\File;

use Gnugat\Redaktilo\File;

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
     * Makes a File of the given $fileType
     *
     * @param string $filename
     *
     * @return File
     */
    public function read($filename)
    {
        $content = @file_get_contents($filename);

        if (empty($content)) {
            $newLineCharacter = PHP_EOL;
        } elseif (false !== strpos($content, self::LINE_BREAK_WINDOWS)) {
            $newLineCharacter = self::LINE_BREAK_WINDOWS;
        } else {
            $newLineCharacter = self::LINE_BREAK_OTHER;
        }

        return new File($filename, $content, $newLineCharacter);
    }

    /**
     * Dumps a File
     *
     * @param File $file
     */
    public function write(File $file)
    {
        file_put_contents($file->getFilename(), $file->getContent());
    }
}
