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

use PhpSpec\ObjectBehavior;

class TokenBuilderSpec extends ObjectBehavior
{
    function it_makes_from_raw()
    {
        $tokens = token_get_all('<?php echo 42;');
        $fromArray = $this->makeFromRaw($tokens[0]);
        $fromString = $this->makeFromRaw($tokens[4]);

        $fromArray->hasNumber()->shouldBe(true);
        $fromArray->getNumber()->shouldBe(T_OPEN_TAG);
        $fromArray->hasValue()->shouldBe(true);
        $fromArray->getValue()->shouldBe('<?php ');
        $fromArray->hasLineNumber()->shouldBe(true);
        $fromArray->getLineNumber()->shouldBe(0);

        $fromString->hasNumber()->shouldBe(false);
        $fromString->hasValue()->shouldBe(true);
        $fromString->getValue()->shouldBe(';');
        $fromString->hasLineNumber()->shouldBe(true);
        $fromString->getLineNumber()->shouldBe(0);
    }

    function it_makes_functions()
    {
        $token = $this->makeFunction();

        $token->hasNumber()->shouldBe(true);
        $token->getNumber()->shouldBe(T_FUNCTION);
        $token->hasValue()->shouldBe(true);
        $token->getValue()->shouldBe('function');
        $token->hasLineNumber()->shouldBe(false);
    }

    function it_makes_methods()
    {
        $token = $this->makeMethod();

        $token->hasNumber()->shouldBe(true);
        $token->getNumber()->shouldBe(T_FUNCTION);
        $token->hasValue()->shouldBe(true);
        $token->getValue()->shouldBe('function');
        $token->hasLineNumber()->shouldBe(false);
    }

    function it_makes_classes()
    {
        $token = $this->makeClass();

        $token->hasNumber()->shouldBe(true);
        $token->getNumber()->shouldBe(T_CLASS);
        $token->hasValue()->shouldBe(true);
        $token->getValue()->shouldBe('class');
        $token->hasLineNumber()->shouldBe(false);
    }

    function it_makes_whitespaces()
    {
        $whitespace = '  ';
        $token = $this->makeWhitespace($whitespace);

        $token->hasNumber()->shouldBe(true);
        $token->getNumber()->shouldBe(T_WHITESPACE);
        $token->hasValue()->shouldBe(true);
        $token->getValue()->shouldBe($whitespace);
        $token->hasLineNumber()->shouldBe(false);
    }

    function it_makes_strings()
    {
        $string = 'meh';
        $token = $this->makeString($string);

        $token->hasNumber()->shouldBe(true);
        $token->getNumber()->shouldBe(T_STRING);
        $token->hasValue()->shouldBe(true);
        $token->getValue()->shouldBe($string);
        $token->hasLineNumber()->shouldBe(false);
    }

    function it_builds_from_raw()
    {
        $rawTokens = token_get_all('<?php echo 42;');
        $tokens = $this->buildFromRaw($rawTokens)->getWrappedObject();

        $openTag = $tokens[0];
        $string = $tokens[4];

        expect($openTag->hasNumber())->toBe(true);
        expect($openTag->getNumber())->toBe(T_OPEN_TAG);
        expect($openTag->hasValue())->toBe(true);
        expect($openTag->getValue())->toBe('<?php ');
        expect($openTag->hasLineNumber())->toBe(true);
        expect($openTag->getLineNumber())->toBe(0);

        expect($string->hasNumber())->toBe(false);
        expect($string->hasValue())->toBe(true);
        expect($string->getValue())->toBe(';');
        expect($string->hasLineNumber())->toBe(true);
        expect($string->getLineNumber())->toBe(0);
    }

    function it_builds_classes()
    {
        $className = 'AppKernel';
        $tokens = $this->buildClass($className);
        $tokens->shouldHaveCount(3);
        $tokens = $tokens->getWrappedObject();

        $classToken = $tokens[0];
        expect($classToken->getNumber())->toBe(T_CLASS);

        $whitespaceToken = $tokens[1];
        expect($whitespaceToken->getNumber())->toBe(T_WHITESPACE);

        $stringToken = $tokens[2];
        expect($stringToken->getNumber())->toBe(T_STRING);
        expect($stringToken->getValue())->toBe($className);
    }

    function it_builds_functions()
    {
        $functionName = 'registerBundles';
        $tokens = $this->buildFunction($functionName);
        $tokens->shouldHaveCount(3);
        $tokens = $tokens->getWrappedObject();

        $functionToken = $tokens[0];
        expect($functionToken->getNumber())->toBe(T_FUNCTION);

        $whitespaceToken = $tokens[1];
        expect($whitespaceToken->getNumber())->toBe(T_WHITESPACE);

        $stringToken = $tokens[2];
        expect($stringToken->getNumber())->toBe(T_STRING);
        expect($stringToken->getValue())->toBe($functionName);
    }

    function it_builds_methods()
    {
        $methodName = 'registerBundles';
        $tokens = $this->buildMethod($methodName);
        $tokens->shouldHaveCount(3);
        $tokens = $tokens->getWrappedObject();

        $functionToken = $tokens[0];
        expect($functionToken->getNumber())->toBe(T_FUNCTION);

        $whitespaceToken = $tokens[1];
        expect($whitespaceToken->getNumber())->toBe(T_WHITESPACE);

        $stringToken = $tokens[2];
        expect($stringToken->getNumber())->toBe(T_STRING);
        expect($stringToken->getValue())->toBe($methodName);
    }
}
