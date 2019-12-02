<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo;

use Gnugat\Redaktilo\Exception\DifferentLineBreaksFoundException;
use Gnugat\Redaktilo\Exception\InvalidArgumentException;
use Gnugat\Redaktilo\Exception\InvalidLineNumberException;
use Gnugat\Redaktilo\Util\StringUtil;

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
     * @param string $lineBreak
     */
    protected function __construct(array $lines, $lineBreak = PHP_EOL)
    {
        $this->setLines($lines);
        $this->lineBreak = $lineBreak;
    }

    /**
     * Creates a Text instance from a string.
     *
     * @param $string
     *
     * @return static
     */
    public static function fromString($string)
    {
        try {
            $lineBreak = StringUtil::detectLineBreak($string);
        } catch (DifferentLineBreaksFoundException $e) {
            $lineBreak = $e->getNumberLineBreakOther() >= $e->getNumberLineBreakWindows()
                ? StringUtil::LINE_BREAK_OTHER
                : StringUtil::LINE_BREAK_WINDOWS;
        }

        return new static(StringUtil::breakIntoLines($string), $lineBreak);
    }

    /**
     * Creates a Text instance from an array of lines.
     *
     * @param string $lineBreak
     *
     * @return static
     */
    public static function fromArray(array $lines, $lineBreak = PHP_EOL)
    {
        return new static($lines, $lineBreak);
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
     * @throws InvalidLineNumberException if $lineNumber is not an integer
     * @throws InvalidLineNumberException if $lineNumber is negative
     * @throws InvalidLineNumberException if $lineNumber is greater or equal than the number of lines
     *
     * @api
     */
    public function setCurrentLineNumber($lineNumber)
    {
        $this->checkIfLineNumberIsValid($lineNumber);
        $this->currentLineNumber = $lineNumber;
    }

    /**
     * @param int $lineNumber
     *
     * @return string
     *
     * @throws InvalidLineNumberException if $lineNumber is not an integer
     * @throws InvalidLineNumberException if $lineNumber is negative
     * @throws InvalidLineNumberException if $lineNumber is greater or equal than the number of lines
     *
     * @api
     */
    public function getLine($lineNumber = null)
    {
        if (null === $lineNumber) {
            $lineNumber = $this->currentLineNumber;
        }
        $this->checkIfLineNumberIsValid($lineNumber);

        return $this->lines[$lineNumber];
    }

    /**
     * @param string $line
     * @param int    $lineNumber
     *
     * @throws InvalidLineNumberException if $lineNumber is not an integer
     * @throws InvalidLineNumberException if $lineNumber is negative
     * @throws InvalidLineNumberException if $lineNumber is greater or equal than the number of lines
     *
     * @api
     */
    public function setLine($line, $lineNumber = null)
    {
        if (null === $lineNumber) {
            $lineNumber = $this->currentLineNumber;
        }
        $this->checkIfLineNumberIsValid($lineNumber);
        $this->lines[$lineNumber] = $line;
    }

    /**
     * Calls the given callback for each line in Text.
     *
     * @param callable $callback
     *
     * @throws InvalidArgumentException if $callback is not callable
     */
    public function map($callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Callback has to be a valid callable, '.gettype($callback).' given.');
        }

        for ($i = 0; $this->checkIfLineNumberIsValid($i, false); ++$i) {
            $this->setCurrentLineNumber($i);
            call_user_func($callback, $this->getLine($i), $i, $this);
        }
    }

    /**
     * @param int $number
     *
     * @throws InvalidLineNumberException if $number is not an integer
     * @throws InvalidLineNumberException if $number is negative
     * @throws InvalidLineNumberException if $number is greater or equal than the length
     * @throws InvalidLineNumberException if the result would be greater or equal than the length
     *
     * @api
     */
    public function incrementCurrentLineNumber($number)
    {
        $this->checkIfLineNumberIsValid($number);
        $newCurrentLineNumber = $this->currentLineNumber + $number;
        $this->checkIfLineNumberIsValid($newCurrentLineNumber);
        $this->currentLineNumber = $newCurrentLineNumber;
    }

    /**
     * @param int $number
     *
     * @throws InvalidLineNumberException if $number is not an integer
     * @throws InvalidLineNumberException if $number is negative
     * @throws InvalidLineNumberException if $number is greater or equal than the length
     * @throws InvalidLineNumberException if the result would be negative
     *
     * @api
     */
    public function decrementCurrentLineNumber($number)
    {
        $this->checkIfLineNumberIsValid($number);
        $newCurrentLineNumber = $this->currentLineNumber - $number;
        $this->checkIfLineNumberIsValid($newCurrentLineNumber);
        $this->currentLineNumber = $newCurrentLineNumber;
    }

    /**
     * @param int $lineNumber
     *
     * @throws InvalidLineNumberException if $lineNumber is not an integer
     * @throws InvalidLineNumberException if $lineNumber is negative
     * @throws InvalidLineNumberException if $lineNumber is greater or equal than the length
     */
    protected function checkIfLineNumberIsValid($lineNumber, $throw = true)
    {
        if (!is_int($lineNumber)) {
            $e = new InvalidLineNumberException($lineNumber, $this, 'The line number should be an integer');
        } elseif ($lineNumber < 0) {
            $e = new InvalidLineNumberException($lineNumber, $this, 'The line number should be positive');
        } elseif ($lineNumber >= $this->length) {
            $e = new InvalidLineNumberException($lineNumber, $this, 'The line number should be strictly lower than the number of lines');
        }

        if (isset($e)) {
            if ($throw) {
                throw $e;
            }

            return false;
        }

        return true;
    }
}
