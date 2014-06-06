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
    /** @var integer */
    private $number;

    /** @var string */
    private $value;

    /** @var integer */
    private $lineNumber;

    /**
     * @param integer $number
     * @param string  $value
     * @param integer $lineNumber
     */
    public function __construct($number = null, $value = null, $lineNumber = null)
    {
        $this->number = $number;
        $this->value = $value;
        $this->lineNumber = $lineNumber;
    }

    /** @return bool */
    public function hasNumber()
    {
        return $this->number !== null;
    }

    /** @return integer */
    public function getNumber()
    {
        return $this->number;
    }

    /** @return bool */
    public function hasValue()
    {
        return $this->value !== null;
    }

    /** @return string */
    public function getValue()
    {
        return $this->value;
    }

    /** @return bool */
    public function hasLineNumber()
    {
        return $this->lineNumber !== null;
    }

    /** @return integer */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    public function isSameAs(Token $token)
    {
        $hasNumberWildcard = ($this->hasNumber() && $token->hasNumber());
        if ($hasNumberWildcard && $this->number !== $token->number) {
            return false;
        }
        $hasValueWildcard = ($this->hasValue() && $token->hasValue());
        if ($hasValueWildcard && $this->value !== $token->value) {
            return false;
        }
        $hasLineNumberWildcard = ($this->hasLineNumber() && $token->hasLineNumber());
        if ($hasLineNumberWildcard && $this->lineNumber !== $token->lineNumber) {
            return false;
        }

        return true;
    }
}
