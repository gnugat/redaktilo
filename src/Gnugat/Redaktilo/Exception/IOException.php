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
 * Thrown if the path isn't accessible or the file could not be written to.
 *
 * @api
 */
class IOException extends \RuntimeException implements Exception
{
    /** @var string */
    private $path;

    /**
     * @param string          $path
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct($path, $message, \Exception $previous = null)
    {
        $this->path = $path;

        parent::__construct($message, 0, $previous);
    }

    /** @return string */
    public function getPath()
    {
        return $this->path;
    }
}
