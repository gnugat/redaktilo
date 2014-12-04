<?php

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
