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
 * If an element isn't supported by any of the registered strategy.
 */
class NotSupportedException extends \Exception
{
    /**
     * @param string $engine
     * @param array  $rawUnsupported
     */
    public function __construct($engine, array $rawUnsupported)
    {
        $formatAsString = function ($element) {
            if (is_string($element) || is_int($element)) {
                return strval($element);
            }

            return 'the given element';
        };

        $message = "$engine hasn't any registered Strategy which support ";
        $unsupported = array_map($formatAsString, $rawUnsupported);
        $message .= implode(' ', $unsupported);

        parent::__construct($message);
    }
}
