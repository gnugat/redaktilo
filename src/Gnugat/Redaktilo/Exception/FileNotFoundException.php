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

use Symfony\Component\Filesystem\Exception\FileNotFoundException as SymfonyFileNotFoundException;

/**
 * Thrown if the file couldn't be opened.
 *
 * @api
 */
class FileNotFoundException extends SymfonyFileNotFoundException implements Exception
{
}
