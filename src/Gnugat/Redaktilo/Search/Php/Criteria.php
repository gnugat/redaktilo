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

class Criteria
{
    /** @var array of Token */
    private $tokens = array();

    /** @param Token $token */
    public function __construct(Token $token)
    {
        $this->tokens[] = $token;
    }

    /** @param Token $token */
    public function followedBy(Token $token)
    {
        $this->tokens[] = $token;
    }

    /** @return array of Token */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @param string $className
     *
     * @return Criteria
     */
    public static function findClass($className)
    {
        $class = Token::makeClass();
        $whitespace = Token::makeWhitespace(Token::NO_VALUE);
        $string = Token::makeString($className);

        $criteria = new self($class);
        $criteria->followedBy($whitespace);
        $criteria->followedBy($string);

        return $criteria;
    }

    /**
     * @param string $functionName
     *
     * @return Criteria
     */
    public static function findFunction($functionName)
    {
        $function = Token::makeFunction();
        $whitespace = Token::makeWhitespace(Token::NO_VALUE);
        $string = Token::makeString($functionName);

        $criteria = new self($function);
        $criteria->followedBy($whitespace);
        $criteria->followedBy($string);

        return $criteria;
    }

    /**
     * @param string $methodName
     *
     * @return Criteria
     */
    public static function findMethod($methodName)
    {
        return self::findFunction($methodName);
    }
}
