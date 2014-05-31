<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Search\Php;

class Token
{
    const NO_VALUE = null;

    /** @var integer */
    private $number;

    /** @var string */
    private $value;

    /**
     * @param integer $number
     * @param string  $value
     */
    public function __construct($number, $value = self::NO_VALUE)
    {
        $this->number = $number;
        $this->value = $value;
    }

    /** @return integer */
    public function getNumber()
    {
        return $this->number;
    }

    /** @return string */
    public function getValue()
    {
        return $this->value;
    }

    /** @return Token */
    public static function makeFunction()
    {
        return new self(T_FUNCTION, 'function');
    }

    /** @return Token */
    public static function makeMethod()
    {
        return self::makeFunction();
    }

    /** @return Token */
    public static function makeClass()
    {
        return new self(T_CLASS, 'class');
    }

    /**
     *
     * @param string $whitespace
     *
     * @return Token
     */
    public static function makeWhitespace($whitespace)
    {
        return new self(T_WHITESPACE, $whitespace);
    }

    /**
     *
     * @param string $string
     *
     * @return Token
     */
    public static function makeString($string)
    {
        return new self(T_STRING, $string);
    }
}
