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
 * Representation of a file:
 *
 * + it has a filename
 * + it has a content which can be read and writen
 * + it has a pointer to a current line
 *
 * Also provides a line representation of the content with some basic
 * manipulations.
 *
 * @api
 */
class File
{
    /** @var string */
    private $filename;

    /** @var string */
    private $content;

    /** @var string */
    private $lineBreak;

    /** @var int */
    private $currentLineNumber = 0;

    /**
     * @param string $filename
     * @param string $content
     * @param string $lineBreak
     */
    public function __construct($filename, $content, $lineBreak = PHP_EOL)
    {
        $this->filename = $filename;
        $this->content = $content;
        $this->lineBreak = $lineBreak;
    }

    /**
     * Returns the absolute path with the file name.
     *
     * @return string
     *
     * @api
     */
    public function getFilename()
    {
        return $this->filename;
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
     * @api
     */
    public function write($newContent)
    {
        return $this->content = $newContent;
    }

    /**
     * Splits the content into an array of lines, stripped of the line break.
     *
     * @return array
     */
    public function readlines()
    {
        return explode($this->lineBreak, $this->content);
    }

    /**
     * Merges the lines using the appropriate line break, and replaces the
     * content with it.
     *
     * @param array $newLines
     */
    public function writelines(array $newLines)
    {
        $this->content = implode($this->lineBreak, $newLines);
    }

    /** @return int */
    public function getCurrentLineNumber()
    {
        return $this->currentLineNumber;
    }

    /** @param int $lineNumber */
    public function setCurrentLineNumber($lineNumber)
    {
        $this->currentLineNumber = $lineNumber;
    }

    /**
     * @param string $line
     * @param int    $lineNumber
     */
    public function changeLineTo($line, $lineNumber)
    {
        $lines = $this->readlines();

        $lines[$lineNumber] = $line;

        $this->writelines($lines);
    }
}
