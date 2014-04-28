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

/**
 * A File full of lines.
 *
 * The content is represented as an array of lines from which the newline
 * character has been stripped.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class LineFile implements File
{
    /** @var string */
    private $filename;

    /** @var string */
    private $content;

    /**
     * @param string $filename
     * @param string $content
     */
    public function __construct($filename, $content)
    {
        $this->filename = $filename;
        $this->content = $content;
    }

    /** {@inheritdoc} */
    public function getFilename()
    {
        return $this->filename;
    }

    /** {@inheritdoc} */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns file's lines.
     *
     * @return array
     */
    public function read()
    {
        $lines = explode(PHP_EOL, $this->content);

        return $lines;
    }

    /**
     * Replaces the file's lines.
     *
     * @param array $content
     */
    public function write($content)
    {
        $this->content = implode(PHP_EOL, $content);
    }
}
