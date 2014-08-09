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
    /** @var string */
    private $filename;

    /** @var string */
    private $content;

    /**
     * @param string $filename
     * @param mixed  $content
     * @param string $lineBreak
     */
    public function __construct($filename, $content, $lineBreak = PHP_EOL)
    {
        $this->filename = $filename;

        if (is_string($content)) {
            $this->content = $content;
            $lineContentConverter = new Converter\LineContentConverter();
            $textFactory = new Factory\TextFactory($lineContentConverter);
            $text = $textFactory->make($content);
            $lines = $text->getLines();
            $lineBreak = $text->getLineBreak();
        } else {
            $this->content = implode($lineBreak, $content);
            $lines = $content;
        }

        parent::__construct($lines, $lineBreak);
    }

    /**
     * @return string
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

    /**
     * Returns the full content loaded in memory (doesn't actually read the
     * file).
     *
     * @return string
     *
     * @api
     */
    public function read()
    {
        return $this->content;
    }

    /**
     * Replaces the full content loaded in memory (doesn't actually write in the
     * file).
     *
     * @param string $newContent
     *
     * @return string
     *
     * @api
     */
    public function write($newContent)
    {
        return $this->content = $newContent;
    }
}
