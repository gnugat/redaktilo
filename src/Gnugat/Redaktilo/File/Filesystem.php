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

        return new File($filename, $content);
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
