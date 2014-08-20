<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Converter;

use Gnugat\Redaktilo\Text;

/**
 * The Text's content is represented as a string.
 *
 * Possible implementations can convert it into:
 *
 * + an array of lines
 * + an array of PHP tokens
 * + etc...
 *
 * @api
 */
interface ContentConverter
{
    /**
     * Returns a converted representation of the given Text's content.
     *
     * @param Text $text
     *
     * @return mixed
     *
     * @api
     */
    public function from(Text $text);

    /**
     * Converts back the representation into the given Text's content.
     *
     * @param Text  $text
     * @param mixed $convertedContent
     *
     * @api
     */
    public function back(Text $text, $convertedContent);
}
