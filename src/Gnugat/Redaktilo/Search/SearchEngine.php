<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\Exception\NotSupportedException;

/**
 * Provides the SearchStrategy which supports the given pattern.
 */
class SearchEngine
{
    /** @var SearchStrategy[][] */
    private $searchStrategies = array();

    /** @var bool */
    private $isSorted = false;

    /**
     * @param SearchStrategy $searchStrategy
     * @param int            $priority
     */
    public function registerStrategy(SearchStrategy $searchStrategy, $priority = 0)
    {
        $this->searchStrategies[$priority][] = $searchStrategy;
        $this->isSorted = false;
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
        if (!$this->isSorted) {
            $this->sortStrategies();
        }

        foreach ($this->searchStrategies as $priority => $searchStrategies) {
            foreach ($searchStrategies as $searchStrategy) {
                if ($searchStrategy->supports($pattern)) {
                    return $searchStrategy;
                }
            }
        }

        throw new NotSupportedException($pattern, $this->searchStrategies);
    }

    /**
     * Sort registered strategies according to their priority
     */
    private function sortStrategies()
    {
        krsort($this->searchStrategies);
        $this->isSorted = true;
    }
}
