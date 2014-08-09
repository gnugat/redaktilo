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
 * Replaces the given location in the given file with the given replacement.
 */
class LineReplaceCommand implements Command
{
    /** {@inheritdoc} */
    public function execute(array $input)
    {
        $file = $input['file'];
        $location = isset($input['location']) ? $input['location'] : $file->getCurrentLineNumber();
        $replacement = $input['replacement'];

        $lines = $file->getLines();
        $lines[$location] = $replacement;
        $file->setLines($lines);

        $file->setCurrentLineNumber($location);
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'replace';
    }
}
