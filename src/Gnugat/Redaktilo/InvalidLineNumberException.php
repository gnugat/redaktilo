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
 * Thrown if the given line number isn't a positive integer strictly inferior to
 * the total number of lines in text.
 *
 * @api
 */
class InvalidLineNumberException extends \InvalidArgumentException
{
    /** @var mixed */
    private $lineNumber;

    /** @var $text */
    private $text;

    /**
     * @param mixed  $lineNumber
     * @param Text   $text
     * @param string $message
     */
    public function __construct($lineNumber, Text $text, $message)
    {
        $this->lineNumber = $lineNumber;
        $this->text = $text;

        parent::__construct($message);
    }

    /** @return mixed */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /** @return Text */
    public function getText()
    {
        return $this->text;
    }
}
