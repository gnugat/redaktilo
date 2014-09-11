<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Search;

/**
 * Provides the SearchStrategy which supports the given pattern.
 */
class SearchEngine
{
    /** @var SearchStrategy[][] */
    private $searchStrategies = array();

    /** @var array */
    private $sorted = array();

    /**
     * @param SearchStrategy $searchStrategy
     * @param int            $priority
     */
    public function registerStrategy(SearchStrategy $searchStrategy, $priority = 0)
    {
        $this->searchStrategies[$priority][] = $searchStrategy;
        $this->sorted = array();
    }

    /**
     * @param mixed $pattern
     *
     * @return SearchStrategy
     *
     * @throws NotSupportedException If the pattern isn't supported by any registered strategy
     */
    public function resolve($pattern)
    {
        if (empty($this->sorted)) {
            $this->sortStrategies();
        }

        foreach ($this->sorted as $searchStrategy) {
            if ($searchStrategy->supports($pattern)) {
                return $searchStrategy;
            }
        }

        throw new NotSupportedException('SearchEngine', $pattern);
    }

    /**
     * Sort registered strategies according to their priority
     */
    private function sortStrategies()
    {
        krsort($this->searchStrategies);
        $this->sorted = array_filter(call_user_func_array('array_merge', $this->searchStrategies));
    }
}
