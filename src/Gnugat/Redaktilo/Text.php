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
    /** @var array */
    protected $lines;

    /** @var int */
    protected $length;

    /** @var string */
    protected $lineBreak;

    /** @var int */
    protected $currentLineNumber = 0;

    /**
     * @param array  $lines
     * @param string $lineBreak
     */
    public function __construct(array $lines, $lineBreak = PHP_EOL)
    {
        $this->setLines($lines);
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
        $this->length = count($lines);
    }

    /**
     * @return int
     *
     * @api
     */
    public function getLength()
    {
        return $this->length;
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
     * @return int
     *
     * @api
     */
    public function getCurrentLineNumber()
    {
        return $this->currentLineNumber;
    }

    /**
     * @param int $lineNumber
     *
     * @throws \InvalidArgumentException if $lineNumber is not an integer
     * @throws \InvalidArgumentException if $lineNumber is negative
     * @throws \InvalidArgumentException if $lineNumber is greater or equal than the number of lines
     *
     * @api
     */
    public function setCurrentLineNumber($lineNumber)
    {
        $this->throwOnInvalidLineNumber($lineNumber);
        $this->currentLineNumber = $lineNumber;
    }

    /**
     * @param int $lineNumber
     *
     * @return string
     *
     * @throws \InvalidArgumentException if $lineNumber is not an integer
     * @throws \InvalidArgumentException if $lineNumber is negative
     * @throws \InvalidArgumentException if $lineNumber is greater or equal than the number of lines
     *
     * @api
     */
    public function getLine($lineNumber = null)
    {
        if (!$lineNumber) {
            $lineNumber = $this->currentLineNumber;
        }
        $this->throwOnInvalidLineNumber($lineNumber);

        return $this->lines[$lineNumber];
    }

    /**
     * @param string $line
     * @param int    $lineNumber
     *
     * @throws \InvalidArgumentException if $lineNumber is not an integer
     * @throws \InvalidArgumentException if $lineNumber is negative
     * @throws \InvalidArgumentException if $lineNumber is greater or equal than the number of lines
     *
     * @api
     */
    public function setLine($line, $lineNumber = null)
    {
        if (!$lineNumber) {
            $lineNumber = $this->currentLineNumber;
        }
        $this->throwOnInvalidLineNumber($lineNumber);
        $this->lines[$lineNumber] = $line;
    }

    /**
     * @param int $lineNumber
     *
     * @throws \InvalidArgumentException if $lineNumber is not an integer
     * @throws \InvalidArgumentException if $lineNumber is negative
     * @throws \InvalidArgumentException if $lineNumber is greater or equal than the number of lines
     */
    protected function throwOnInvalidLineNumber($lineNumber)
    {
        if (!is_int($lineNumber)) {
            throw new \InvalidArgumentException('The line number should be an integer');
        }
        if ($lineNumber < 0) {
            throw new \InvalidArgumentException('The line number should be positive');
        }
        if ($lineNumber >= $this->length) {
            throw new \InvalidArgumentException('The line number should be strictly lower than the number of lines');
        }
    }
}
