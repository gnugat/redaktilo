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
    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If replacement isn't valid
     */
    public function execute(array $input)
    {
        /** @var \Gnugat\Redaktilo\Text $text */
        $text = $input['text'];
        $location = isset($input['location']) ? intval($input['location']) : $text->getCurrentLineNumber();
        if (is_string($input['replacement'])) {
            // @deprecated 1.1 use $text->setLine($replacement, $location) instead
            $replacement = $input['replacement'];
        } else if (is_callable($input['replacement'])) {
            $line = $text->getLine($location);
            $replacement = $input['replacement']($line);
        } else {
            throw new \InvalidArgumentException('Invalid replacement');
        }

        $text->setLine($replacement, $location);
        $text->setCurrentLineNumber($location);
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'replace';
    }
}
