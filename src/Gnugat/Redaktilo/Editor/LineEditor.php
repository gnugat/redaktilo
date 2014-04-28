<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Editor;

use Gnugat\Redaktilo\File\Filesystem;
use Gnugat\Redaktilo\File\File;

/**
 * An editor which manipulates LineFile.
 */
class LineEditor implements Editor
{
    /** @var Filesystem */
    private $filesystem;

    /** @var array of File */
    private $files = array();

    /** @var integer */
    private $currentFile = -1;

    /** @var Boolean */
    private $autosave = true;

    /** @param Filesystem $filesystem */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /** {@inheritdoc} */
    public function open($filename)
    {
        $file = $this->filesystem->read($filename, Filesystem::LINE_FILE_TYPE);

        $this->files[] = $file;
        $this->currentFile++;
    }

    /**
     * @param string $add
     * @param string $after
     */
    public function addAfter($add, $after)
    {
        $file = $this->files[$this->currentFile];
        $preEditLines = $file->read();
        $postEditLines = array();
        foreach ($preEditLines as $line) {
            $postEditLines[] = $line;
            if ($line === $after) {
                $postEditLines[] = $add;
            }
        }
        $file->write($postEditLines);
        if ($this->autosave) {
            $this->filesystem->write($file);
        }
    }
}
