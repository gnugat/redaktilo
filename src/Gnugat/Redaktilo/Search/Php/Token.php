<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Search\Php;

/**
 * @deprecated 1.7 No replacement
 */
class Token
{
    /** @var int */
    private $number;

    /** @var string */
    private $value;

    /** @var int */
    private $lineNumber;

    /**
     * @param int    $number
     * @param string $value
     * @param int    $lineNumber
     */
    public function __construct($number = null, $value = null, $lineNumber = null)
    {
        trigger_error(__CLASS__.' is no longer supported and will be removed in version 2', \E_USER_DEPRECATED);
        $this->number = $number;
        $this->value = $value;
        $this->lineNumber = $lineNumber;
    }

    /** @return bool */
    public function hasNumber()
    {
        return null !== $this->number;
    }

    /** @return int */
    public function getNumber()
    {
        return $this->number;
    }

    /** @return bool */
    public function hasValue()
    {
        return null !== $this->value;
    }

    /** @return string */
    public function getValue()
    {
        return $this->value;
    }

    /** @return bool */
    public function hasLineNumber()
    {
        return null !== $this->lineNumber;
    }

    /** @return int */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
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
