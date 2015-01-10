<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Exception;

/**
 * Thrown if the pattern given to SearchEngine isn't supported by any of its
 * registered strategies.
 *
 * @api
 */
class NotSupportedException extends \LogicException implements Exception
{
    /** @var mixed */
    private $pattern;

    /** @var array */
    private $searchStrategies;

    /**
     * @param mixed $pattern
     * @param array $searchStrategies
     */
    public function __construct($pattern, array $searchStrategies)
    {
        $this->pattern = $pattern;
        $this->searchStrategies = $searchStrategies;

        $patternMessage = 'given pattern';
        if (is_string($pattern) || is_int($pattern)) {
            $patternMessage .= ' "'.strval($pattern).'"';
        }

        $message = sprintf(
            'The %s isn\'t supported by the Search Strategies registered in SearchEngine',
            $patternMessage
        );

        parent::__construct($message);
    }

    /** @return mixed */
    public function getPattern()
    {
        return $this->pattern;
    }

    /** @return array */
    public function getSearchStrategies()
    {
        return $this->searchStrategies;
    }
}
