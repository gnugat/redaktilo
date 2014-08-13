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
 * Replaces the given location in the given text with the given replacement.
 */
class LineReplaceCommand implements Command
{
    /** {@inheritdoc} */
    public function execute(array $input)
    {
        $text = $input['text'];
        $location = isset($input['location']) ? intval($input['location']) : $text->getCurrentLineNumber();
        $replacement = $input['replacement'];

        $lines = $text->getLines();
        $lines[$location] = $replacement;
        $text->setLines($lines);

        $text->setCurrentLineNumber($location);
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'replace';
    }
}
