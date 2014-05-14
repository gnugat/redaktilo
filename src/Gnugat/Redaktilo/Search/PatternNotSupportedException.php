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
 * If the pattern given to SearchEngine isn't supported by any of its registered
 * SearchStrategy.
 *
 * @api
 */
class PatternNotSupportedException extends \Exception
{
    /** @param mixed $pattern */
    public function __construct($pattern)
    {
        $messageBits[] = 'The given pattern';
        if (is_string($pattern) || is_int($pattern)) {
            $messageBits[] = sprintf('"%s"', $pattern);
        }
        $messageBits[] = 'isn\'t supported by any registered SearchStrategy';

        $message = implode(' ', $messageBits);

        parent::__construct($message);
    }
}
