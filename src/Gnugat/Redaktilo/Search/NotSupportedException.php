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
 * Thrown if the pattern given to SearchEngine isn't supported by any of its
 * registered strategies.
 *
 * @api
 *
 * @deprecated since 1.4, use the class from the Exception namespace instead
 */
abstract class NotSupportedException extends \Exception
{
    /** @return mixed */
    abstract public function getPattern();

    /** @return array */
    abstract public function getSearchStrategies();
}
