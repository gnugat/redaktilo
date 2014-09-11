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
 * Moves to the absolute position given in percentage.
 *
 * The percentage is given as an integer comprised between 0 and 100.
 */
class LineJumpPercentCommand implements Command
{
    /** {@inheritdoc} */
    public function execute(array $input)
    {
        /** @var \Gnugat\Redaktilo\Text $text */
        $text = $input['text'];
        $number = (isset($input['number']) ? intval($input['number']) : 100);
        if ($number < 0 || $number > 100) {
            throw new \InvalidArgumentException(sprintf('The percentage should be between 0 and 100, %s given', $number));
        }

        $lastLineNumber = $text->getLength() - 1;
        $lineNumber = ceil($number * $lastLineNumber / 100);
        $text->setCurrentLineNumber(intval($lineNumber));
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'jump_percent';
    }
}
