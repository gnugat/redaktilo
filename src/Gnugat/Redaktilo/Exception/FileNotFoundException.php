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
 * Thrown if the file couldn't be opened.
 *
 * @api
 */
class FileNotFoundException extends \RuntimeException implements Exception
{
    /** @var string */
    private $path;

    /**
     * @param string $path
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
