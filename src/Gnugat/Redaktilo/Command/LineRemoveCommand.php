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
 * Removes the given location in the given text.
 */
class LineRemoveCommand implements Command
{
    /** {@inheritdoc} */
    public function execute(array $input)
    {
        $text = $input['text'];
        $location = isset($input['location']) ? intval($input['location']) : $text->getCurrentLineNumber();

        $lines = $text->getLines();
        unset($lines[$location]);
        $text->setLines($lines);

        $lineNumber = ($location === count($lines)) ? $location - 1 : $location;
        $text->setCurrentLineNumber($lineNumber);
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'remove';
    }
}
