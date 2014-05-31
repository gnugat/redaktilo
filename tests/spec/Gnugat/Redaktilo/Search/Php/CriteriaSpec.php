<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Search\Php;

use Gnugat\Redaktilo\Search\Php\Criteria;
use Gnugat\Redaktilo\Search\Php\Token;
use PhpSpec\ObjectBehavior;

class CriteriaSpec extends ObjectBehavior
{
    function it_has_tokens(Token $class, Token $whitespace, Token $string)
    {
        $this->beConstructedWith($class);
        $this->followedBy($whitespace);
        $this->followedBy($string);

        $expectedTokens = array($class, $whitespace, $string);

        $this->getTokens()->shouldBe($expectedTokens);
    }

    function it_finds_classes()
    {
        $className = 'AppKernel';
        $criteria = Criteria::findClass($className);
        $tokens = $criteria->getTokens();

        $tokenNumber = count($tokens);
        expect($tokenNumber)->toBe(3);

        $classToken = $tokens[0];
        expect($classToken->getNumber())->toBe(T_CLASS);

        $whitespaceToken = $tokens[1];
        expect($whitespaceToken->getNumber())->toBe(T_WHITESPACE);

        $stringToken = $tokens[2];
        expect($stringToken->getNumber())->toBe(T_STRING);
        expect($stringToken->getValue())->toBe($className);
    }

    function it_finds_functions()
    {
        $functionName = 'registerBundles';
        $criteria = Criteria::findFunction($functionName);
        $tokens = $criteria->getTokens();

        $tokenNumber = count($tokens);
        expect($tokenNumber)->toBe(3);

        $functionToken = $tokens[0];
        expect($functionToken->getNumber())->toBe(T_FUNCTION);

        $whitespaceToken = $tokens[1];
        expect($whitespaceToken->getNumber())->toBe(T_WHITESPACE);

        $stringToken = $tokens[2];
        expect($stringToken->getNumber())->toBe(T_STRING);
        expect($stringToken->getValue())->toBe($functionName);
    }

    function it_finds_methods()
    {
        $methodName = 'registerBundles';
        $criteria = Criteria::findMethod($methodName);
        $tokens = $criteria->getTokens();

        $tokenNumber = count($tokens);
        expect($tokenNumber)->toBe(3);

        $functionToken = $tokens[0];
        expect($functionToken->getNumber())->toBe(T_FUNCTION);

        $whitespaceToken = $tokens[1];
        expect($whitespaceToken->getNumber())->toBe(T_WHITESPACE);

        $stringToken = $tokens[2];
        expect($stringToken->getNumber())->toBe(T_STRING);
        expect($stringToken->getValue())->toBe($methodName);
    }
}
