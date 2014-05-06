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
 * A data source which contains:
 *
 * + the path to the file
 * + the raw content
 * + a pointer to the current line
 *
 * Its read and write methods provide a representation of the content:
 * an array of lines from which the newline character has been stripped.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class File
{
    /** @var string */
    private $filename;

    /** @var string */
    private $content;

    /** @var string */
    private $newLineCharacter;

    /** @var integer */
    private $currentLineNumber = 0;

    /**
     * @param string $filename
     * @param string $content
     */
    public function __construct($filename, $content, $newLineCharacter = PHP_EOL)
    {
        $this->filename = $filename;
        $this->content = $content;
        $this->newLineCharacter = $newLineCharacter;
    }

    /** @return string */
    public function getFilename()
    {
        return $this->filename;
    }

    /** @return string */
    public function read()
    {
        return $this->content;
    }

    /** @param string $newContent */
    public function write($newContent)
    {
        return $this->content = $newContent;
    }

    /** @return array of lines stripped of the newline character */
    public function readlines()
    {
        return explode($this->newLineCharacter, $this->content);
    }

    /** @param array $newLines */
    public function writelines(array $newLines)
    {
        $this->content = implode($this->newLineCharacter, $newLines);
    }

    /** @return integer */
    public function getCurrentLineNumber()
    {
        return $this->currentLineNumber;
    }

    /** @param integer $lineNumber */
    public function setCurrentLineNumber($lineNumber)
    {
        $this->currentLineNumber = $lineNumber;
    }

    /**
     * @param string $line
     * @param string $lineNumber
     */
    public function insertLineAt($line, $lineNumber)
    {
        $lines = $this->readlines();

        array_splice($lines, $lineNumber, 0, $line);

        $this->writelines($lines);
    }
}
