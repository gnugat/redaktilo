<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Factory;

use Gnugat\Redaktilo\Converter\LineContentConverter;
use Gnugat\Redaktilo\Text;

/**
 * Stateless service which creates a Text from the given string.
 */
class TextFactory
{
    /** @var LineContentConverter */
    private $lineContentConverter;

    /** @param LineContentConverter $lineContentConverter */
    public function __construct(LineContentConverter $lineContentConverter)
    {
        $this->lineContentConverter = $lineContentConverter;
    }

    /**
     * @param string $string
     *
     * @return Text
     */
    public function make($string)
    {
        $lineBreak = $this->lineContentConverter->detectLineBreak($string);
        $lines = explode($lineBreak, $string);

        return new Text($lines, $lineBreak);
    }
}
