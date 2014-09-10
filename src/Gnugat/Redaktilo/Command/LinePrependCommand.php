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
 * Prepends a string at the begining of the given location (by default the
 * current line).
 */
class LinePrependCommand implements Command
{
    /** {@inheritdoc} */
    public function execute(array $input)
    {
        /** @var \Gnugat\Redaktilo\Text $text */
        $text = $input['text'];
        $location = isset($input['location']) ? intval($input['location']) : $text->getCurrentLineNumber();
        $suffix = strval($input['value']);

        $lines = $text->getLines();
        $lines[$location] = $suffix.$lines[$location];
        $text->setLines($lines);
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'prepend';
    }
}
