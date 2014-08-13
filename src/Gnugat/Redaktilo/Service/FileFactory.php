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

/**
 * Stateless service which creates a File from the given filename.
 */
class FileFactory
{
    /** @var LineBreak */
    private $lineBreak;

    /** @param LineBreak $lineBreak */
    public function __construct(LineBreak $lineBreak)
    {
        $this->lineBreak = $lineBreak;
    }

    /**
     * @param string $filename
     * @param string $content
     *
     * @return File
     */
    public function make($filename, $content)
    {
        $lineBreak = $this->lineBreak->detect($content);
        $lines = explode($lineBreak, $content);

        return new File($filename, $lines, $lineBreak);
    }
}
