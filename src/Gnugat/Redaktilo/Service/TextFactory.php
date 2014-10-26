<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Service;

use Gnugat\Redaktilo\Exception\DifferentLineBreaksFoundException as AliasedDifferentLineBreaksFoundException;
use Gnugat\Redaktilo\Text;

/**
 * Stateless service which creates a Text from the given string.
 */
class TextFactory
{
    /** @var LineBreak */
    private $lineBreak;

    /** @param LineBreak $lineBreak */
    public function __construct(LineBreak $lineBreak)
    {
        $this->lineBreak = $lineBreak;
    }

    /**
     * @param string $string
     *
     * @return Text
     */
    public function make($string)
    {
        try {
            $lineBreak = $this->lineBreak->detect($string);
        } catch (AliasedDifferentLineBreaksFoundException $e) {
            $lineBreak = $e->getNumberLineBreakOther() >= $e->getNumberLineBreakWindows()
                ? LineBreak::LINE_BREAK_OTHER
                : LineBreak::LINE_BREAK_WINDOWS;
        }

        $lines = preg_split('/\R/', $string);

        return new Text($lines, $lineBreak);
    }
}
