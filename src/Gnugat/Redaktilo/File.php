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

/**
 * Redaktilo's base entity representing a file: it is a Text which has a
 * filename (which is the absolute path with the file name).
 *
 * @api
 */
class File extends Text
{
    /** @var string|null */
    private $filename;

    /**
     * @return string|null
     *
     * @api
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     *
     * @api
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }
}
