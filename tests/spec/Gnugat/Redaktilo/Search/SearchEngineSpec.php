<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\Search\SearchStrategy;
use PhpSpec\ObjectBehavior;

class SearchEngineSpec extends ObjectBehavior
{
    function let(SearchStrategy $searchStrategy)
    {
        $this->registerStrategy($searchStrategy);
    }

    function it_sort_registered_strategies(
        SearchStrategy $searchStrategy1,
        SearchStrategy $searchStrategy2,
        SearchStrategy $searchStrategy3
    ) {
        $pattern = '/ac/';

        $searchStrategy1->supports($pattern)->willReturn(true);
        $searchStrategy2->supports($pattern)->willReturn(true);
        $searchStrategy3->supports($pattern)->willReturn(true);

        $this->registerStrategy($searchStrategy1, 10);
        $this->registerStrategy($searchStrategy2, 0);
        $this->registerStrategy($searchStrategy3, 20);

        $this->resolve($pattern)->shouldReturn($searchStrategy3);
    }

    function it_resolves_registered_strategies(SearchStrategy $searchStrategy)
    {
        $pattern = 'We are now no longer the Knights who say Ni.';

        $searchStrategy->supports($pattern)->willReturn(true);

        $this->resolve($pattern)->shouldBe($searchStrategy);
    }

    function it_fails_when_the_strategy_is_not_supported(SearchStrategy $searchStrategy)
    {
        $pattern = 'We are now no longer the Knights who say Ni.';
        $exception = '\Gnugat\Redaktilo\Exception\NotSupportedException';

        $searchStrategy->supports($pattern)->willReturn(false);

        $this->shouldThrow($exception)->duringResolve($pattern);
    }
}
