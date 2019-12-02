<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Service;

use Gnugat\Redaktilo\Text;

/**
 * Converts a Text back to a string content.
 */
class ContentFactory
{
    /**
     * @return string
     */
    public function make(Text $text)
    {
        return implode($text->getLineBreak(), $text->getLines());
    }
}
