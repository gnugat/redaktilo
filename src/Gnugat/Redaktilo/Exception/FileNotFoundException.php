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
 *
 * @todo Make this exception extends \RuntimeException in Redaktilo v2
 */
class FileNotFoundException extends SymfonyFileNotFoundException implements Exception
{
    /** @var string */
    private $path;

    /**
     * @param string          $path
     * @param \Exception|null $previous
     */
    public function __construct($path, \Exception $previous = null)
    {
        $this->path = $path;

        $message = sprintf('Failed to open "%s" because it does not exist.', $path);

        parent::__construct($message, 0, $previous);
    }

    /** @return string */
    public function getPath()
    {
        return $this->path;
    }
}
