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

use Gnugat\Redaktilo\Search\Php\Token;
use PhpSpec\ObjectBehavior;

class TokenSpec extends ObjectBehavior
{
    const NUMBER = T_STRING;
    const VALUE = 'registerBundles';

    function let()
    {
        $this->beConstructedWith(self::NUMBER, self::VALUE);
    }

    function it_has_a_number()
    {
        $this->getNumber()->shouldBe(self::NUMBER);
    }

    function it_has_a_value()
    {
        $this->getValue()->shouldBe(self::VALUE);
    }

    function it_makes_functions()
    {
        $token = Token::makeFunction();

        expect($token->getNumber())->toBe(T_FUNCTION);
        expect($token->getValue())->toBe('function');
    }

    function it_makes_methods()
    {
        $token = Token::makeMethod();

        expect($token->getNumber())->toBe(T_FUNCTION);
        expect($token->getValue())->toBe('function');
    }

    function it_makes_classes()
    {
        $token = Token::makeClass();

        expect($token->getNumber())->toBe(T_CLASS);
        expect($token->getValue())->toBe('class');
    }

    function it_makes_whitespaces()
    {
        $whitespace = '  ';
        $token = Token::makeWhitespace($whitespace);

        expect($token->getNumber())->toBe(T_WHITESPACE);
        expect($token->getValue())->toBe($whitespace);
    }

    function it_makes_strings()
    {
        $string = 'meh';
        $token = Token::makeString($string);

        expect($token->getNumber())->toBe(T_STRING);
        expect($token->getValue())->toBe($string);
    }
}
