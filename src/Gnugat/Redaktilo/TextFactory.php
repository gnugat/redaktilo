<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo;

use Gnugat\Redaktilo\Converter\LineContentConverter;

/**
 * Stateless service which creates a Text from the given string.
 *
 * @api
 */
class TextFactory
{
    /** @var LineContentConverter */
    private $lineContentConverter;

    /** @var LineContentConverter */
    public function __construct(LineContentConverter $lineContentConverter)
    {
        $this->lineContentConverter = $lineContentConverter;
    }

    /**
     * @param string $string
     *
     * @return Text
     *
     * @api
     */
    public function make($string)
    {
        $lineBreak = $this->lineContentConverter->detectLineBreak($string);
        $lines = explode($lineBreak, $string);

        return new Text($lines, $lineBreak);
    }
}
