<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\FactoryMethod;

/**
 * A convenient wrapper to keep readability when manipulating the filesystem.
 *
 * @api
 */
class Filesystem
{
    /** @return bool */
    public static function forceCreation()
    {
        return true;
    }
}
