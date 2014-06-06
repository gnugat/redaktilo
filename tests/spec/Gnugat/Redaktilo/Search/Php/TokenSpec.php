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
    function it_can_have_a_number()
    {
        $number = T_STRING;
        $withoutNumber = new Token();
        $withNumber = new Token($number);

        expect($withoutNumber->hasNumber())->toBe(false);
        expect($withNumber->hasNumber())->toBe(true);
        expect($withNumber->getNumber())->toBe($number);
    }

    function it_can_have_a_value()
    {
        $value = 'registerBundles';
        $withoutValue = new Token();
        $withValue = new Token(null, $value);

        expect($withoutValue->hasValue())->toBe(false);
        expect($withValue->hasValue())->toBe(true);
        expect($withValue->getValue())->toBe($value);
    }

    function it_can_have_a_line_number()
    {
        $lineNumber = 42;
        $withoutLineNumber = new Token();
        $withLineNumber = new Token(null, null, $lineNumber);

        expect($withoutLineNumber->hasLineNumber())->toBe(false);
        expect($withLineNumber->hasLineNumber())->toBe(true);
        expect($withLineNumber->getLineNumber())->toBe($lineNumber);
    }

    function it_can_be_compared()
    {
        $full = new Token(T_STRING, 'meh', 42);
        $sameAsFull = new Token(T_STRING, 'meh', 42);

        expect($full->isSameAs($sameAsFull))->toBe(true);

        $differentNumber = new Token(T_WHITESPACE, 'meh', 42);
        $differentValue = new Token(T_STRING, 'hem', 42);
        $differentLineNumber = new Token(T_STRING, 'meh', 23);

        expect($full->isSameAs($differentNumber))->toBe(false);
        expect($full->isSameAs($differentValue))->toBe(false);
        expect($full->isSameAs($differentLineNumber))->toBe(false);

        $wildcardNumber = new Token(null, 'meh', 42);
        $wildcardValue = new Token(T_STRING, null, 42);
        $wildcardLineNumber = new Token(T_STRING, 'meh', null);

        expect($full->isSameAs($wildcardNumber))->toBe(true);
        expect($full->isSameAs($wildcardValue))->toBe(true);
        expect($full->isSameAs($wildcardLineNumber))->toBe(true);
    }
}
