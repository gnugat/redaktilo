<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Replace;

use Gnugat\Redaktilo\Search\PatternNotSupportedException;

/**
 * Holds ReplaceStrategy, and provides the appropriate one according to the given
 * pattern.
 *
 * @api
 */
class ReplaceEngine
{
    /** @var array of ReplaceStrategy */
    private $repalceStrategies = array();

    /**
     * @param ReplaceStrategy $searchEngine
     *
     * @api
     */
    public function registerStrategy(ReplaceStrategy $replaceStrategy)
    {
        $this->repalceStrategies[] = $replaceStrategy;
    }

    /**
     * @param mixed $pattern
     *
     * @return ReplaceStrategy
     *
     * @throws PatternNotSupportedException If the pattern isn't supported by any registered strategy
     *
     * @api
     */
    public function resolve($pattern)
    {
        foreach ($this->repalceStrategies as $replaceStrategy) {
            if ($replaceStrategy->supports($pattern)) {
                return $replaceStrategy;
            }
        }

        throw new PatternNotSupportedException($pattern);
    }
}
