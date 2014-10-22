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

use Symfony\Component\Filesystem\Exception\IOException as SymfonyIOException;

/**
 * Thrown if the path isn't accessible or the file could not be written to.
 *
 * @api
 */
class IOException extends SymfonyIOException implements Exception
{
}
