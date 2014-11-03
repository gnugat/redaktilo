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
 * Thrown if the pattern given to the SearchEngine couldn't match anything in
 * the Text.
 *
 * @api
 *
 * @deprecated since 1.4, use the class from the Exception namespace instead
 */
abstract class PatternNotFoundException extends \Exception
{
    /** @return mixed */
    abstract public function getPattern();

    /** @return \Gnugat\Redaktilo\Text */
    abstract public function getText();
}
