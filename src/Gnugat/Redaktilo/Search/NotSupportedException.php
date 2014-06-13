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
     * @param mixed  $rawUnsupported
     */
    public function __construct($engine, $rawUnsupported)
    {
        $element = 'the given element';
        if (is_string($rawUnsupported) || is_int($rawUnsupported)) {
            $element = strval($rawUnsupported);
        }

        $message = "$engine hasn't any registered Strategy which support $element";

        parent::__construct($message);
    }
}
