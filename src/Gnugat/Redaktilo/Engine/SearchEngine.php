<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Engine;

use Gnugat\Redaktilo\Search\SearchStrategy;

/**
 * Holds SearchStrategy, and provides the appropriate one according to the given
 * pattern.
 *
 * @api
 */
class SearchEngine
{
    /** @var array of SearchStrategy */
    private $searchStrategies = array();

    /**
     * @param SearchStrategy $searchEngine
     *
     * @api
     */
    public function registerStrategy(SearchStrategy $searchStrategy)
    {
        $this->searchStrategies[] = $searchStrategy;
    }

    /**
     * @param mixed $pattern
     *
     * @return SearchStrategy
     *
     * @throws NotSupportedException If the pattern isn't supported by any registered strategy
     *
     * @api
     */
    public function resolve($pattern)
    {
        foreach ($this->searchStrategies as $searchStrategy) {
            if ($searchStrategy->supports($pattern)) {
                return $searchStrategy;
            }
        }

        throw new NotSupportedException('SearchEngine', array($pattern));
    }
}
