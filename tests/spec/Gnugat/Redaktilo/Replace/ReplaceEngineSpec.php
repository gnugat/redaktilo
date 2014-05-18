<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Replace;

use Gnugat\Redaktilo\Replace\ReplaceStrategy;
use PhpSpec\ObjectBehavior;

class ReplaceEngineSpec extends ObjectBehavior
{
    function let(ReplaceStrategy $replaceStrategy)
    {
        $this->registerStrategy($replaceStrategy);
    }

    function it_resolves_registered_strategies(ReplaceStrategy $replaceStrategy)
    {
        $pattern = 'We are now no longer the Knights who say Ni.';

        $replaceStrategy->supports($pattern)->willReturn(true);

        $this->resolve($pattern)->shouldBe($replaceStrategy);
    }

    function it_fails_when_the_strategy_is_not_supported(ReplaceStrategy $replaceStrategy)
    {
        $pattern = 'We are now no longer the Knights who say Ni.';
        $exception = 'Gnugat\Redaktilo\Search\PatternNotSupportedException';

        $replaceStrategy->supports($pattern)->willReturn(false);

        $this->shouldThrow($exception)->duringResolve($pattern);
    }
}
