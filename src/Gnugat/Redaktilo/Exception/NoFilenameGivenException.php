<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Exception;

/**
 * @author Wouter J <wouter@wouterj.nl>
 */
class NoFilenameGivenException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('No filename given when saving the file.');
    }
}
