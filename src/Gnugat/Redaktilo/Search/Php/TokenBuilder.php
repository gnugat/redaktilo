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

class TokenBuilder
{
    /**
     * @param mixed $rawToken
     * @param int   $lineNumber
     *
     * @return Token
     */
    public function makeFromRaw($rawToken, $lineNumber = 0)
    {
        if (!is_array($rawToken)) {
            return new Token(null, $rawToken, $lineNumber);
        }

        return new Token($rawToken[0], $rawToken[1], $rawToken[2] - 1);
    }

    /** @return Token */
    public function makeFunction()
    {
        return new Token(T_FUNCTION, 'function');
    }

    /** @return Token */
    public function makeMethod()
    {
        return $this->makeFunction();
    }

    /** @return Token */
    public function makeClass()
    {
        return new Token(T_CLASS, 'class');
    }

    /**
     * @param string $whitespace
     *
     * @return Token
     */
    public function makeWhitespace($whitespace)
    {
        return new Token(T_WHITESPACE, $whitespace);
    }

    /**
     *
     * @param string $string
     *
     * @return Token
     */
    public function makeString($string)
    {
        return new Token(T_STRING, $string);
    }

    /**
     * @param array $rawTokens
     *
     * @return Token[]
     */
    public function buildFromRaw(array $rawTokens)
    {
        $lineNumber = 0;
        $tokens = array();
        foreach ($rawTokens as $rawToken) {
            $token = $this->makeFromRaw($rawToken, $lineNumber);
            if ($token->hasNumber()) {
                $lineNumber = $token->getLineNumber() + substr_count($token->getValue(), "\n");
            }
            $tokens[] = $token;
        }

        return $tokens;
    }

    /**
     * @param string $className
     *
     * @return Token[]
     */
    public function buildClass($className)
    {
        $class = $this->makeClass();
        $whitespace = $this->makeWhitespace(null);
        $string = $this->makeString($className);

        return array($class, $whitespace, $string);
    }

    /**
     * @param string $functionName
     *
     * @return Token[]
     */
    public function buildFunction($functionName)
    {
        $function = $this->makeFunction();
        $whitespace = $this->makeWhitespace(null);
        $string = $this->makeString($functionName);

        return array($function, $whitespace, $string);
    }

    /**
     * @param string $methodName
     *
     * @return Token[]
     */
    public function buildMethod($methodName)
    {
        return $this->buildFunction($methodName);
    }
}
