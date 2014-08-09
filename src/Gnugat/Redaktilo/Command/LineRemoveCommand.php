<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Command;

/**
 * Removes the given location in the given file.
 */
class LineRemoveCommand implements Command
{
    /** {@inheritdoc} */
    public function execute(array $input)
    {
        $file = $input['file'];
        $location = isset($input['location']) ? $input['location'] : $file->getCurrentLineNumber();

        $lines = $file->getLines();
        unset($lines[$location]);
        $file->setLines($lines);

        $lineNumber = $location == count($lines) ? $location-1 : $location;
        $file->setCurrentLineNumber($lineNumber);
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'remove';
    }
}
