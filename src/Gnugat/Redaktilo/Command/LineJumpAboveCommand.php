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
 * Moves the cursor to x lines above the current one.
 */
class LineJumpAboveCommand implements Command
{
    /** {@inheritdoc} */
    public function execute(array $input)
    {
        /** @var \Gnugat\Redaktilo\Text $text */
        $text = $input['text'];
        $number = (isset($input['number']) ? intval($input['number']) : 1);

        $currentLineNumber = $text->getCurrentLineNumber();
        $lineNumber = $currentLineNumber - $number;
        $text->setCurrentLineNumber($lineNumber);
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'jump_above';
    }
}
