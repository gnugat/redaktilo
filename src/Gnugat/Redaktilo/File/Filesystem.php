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
    const LINE_FILE_TYPE = 'Gnugat\Redaktilo\File';

    /**
     * Makes a File of the given $fileType
     *
     * @param string $filename
     * @param string $fileType Only Filesystem::*_TYPE constants are supported
     *
     * @return File of the given $fileType
     *
     * @throws Exception If the file type isn't supported
     */
    public function read($filename, $fileType)
    {
        if (!$this->supports($fileType)) {
            throw new \Exception(sprintf('Given file type "%s" not supported', $fileType));
        }
        $content = @file_get_contents($filename);

        return new $fileType($filename, $content);
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

    /**
     * @param string $fileType
     *
     * @return Boolean
     */
    private function supports($fileType)
    {
        $supportedTypes = array(
            self::LINE_FILE_TYPE,
        );

        return in_array($fileType, $supportedTypes);
    }
}
