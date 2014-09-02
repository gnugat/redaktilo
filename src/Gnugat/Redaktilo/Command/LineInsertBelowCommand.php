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
 * Inserts the given addition in the given text below the given location.
 */
class LineInsertBelowCommand implements Command
{
    /** {@inheritdoc} */
    public function execute(array $input)
    {
        /** @var \Gnugat\Redaktilo\Text $text */
        $text = $input['text'];
        $location = 1 + (isset($input['location']) ? intval($input['location']) : $text->getCurrentLineNumber());
        $addition = $input['addition'];

        $lines = $text->getLines();
        array_splice($lines, $location, 0, $addition);
        $text->setLines($lines);

        $text->setCurrentLineNumber($location);
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'insert_below';
    }
}
