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
 * Redaktilo's base entity representing a collection of lines: each line is
 * stripped from its line break character.
 * This character is centralized in a property.
 *
 * When Text is created, the current line number is set to 0.
 *
 * @api
 */
class Text
{
    /**
     * @var array
     */
    private $lines;

    /**
     * @var string
     */
    private $lineBreak;

    /**
     * @var ineger
     */
    private $currentLineNumber = 0;

    /**
     * @param array  $lines
     * @param string $lineBreak
     *
     * @api
     */
    public function __construct(array $lines, $lineBreak = PHP_EOL)
    {
        $this->lines = $lines;
        $this->lineBreak = $lineBreak;
    }

    /**
     * @return array
     *
     * @api
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @param array $lines
     *
     * @api
     */
    public function setLines(array $lines)
    {
        $this->lines = $lines;
    }

    /**
     * @return string
     *
     * @api
     */
    public function getLineBreak()
    {
        return $this->lineBreak;
    }

    /**
     * @param string $lineBreak
     *
     * @api
     */
    public function setLineBreak($lineBreak)
    {
        $this->lineBreak = $lineBreak;
    }

    /**
     * @return integer
     *
     * @api
     */
    public function getCurrentLineNumber()
    {
        return $this->currentLineNumber;
    }

    /**
     * @param integer $lineNumber
     *
     * @api
     */
    public function setCurrentLineNumber($lineNumber)
    {
        $this->currentLineNumber = $lineNumber;
    }
}
