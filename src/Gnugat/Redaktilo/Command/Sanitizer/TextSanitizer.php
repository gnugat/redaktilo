<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Command\Sanitizer;

use Gnugat\Redaktilo\Exception\InvalidArgumentException;
use Gnugat\Redaktilo\Text;

class TextSanitizer implements InputSanitizer
{
    /**
     * {@inheritdoc}
     *
     * @return Text
     *
     * @throws InvalidArgumentException If the text parameter is missing
     * @throws InvalidArgumentException If the text parameter is not an instance of Text
     */
    public function sanitize(array $input)
    {
        if (!isset($input['text'])) {
            throw new InvalidArgumentException('A \'text\' entry should have been given in the input array');
        }
        if (!is_object($input['text']) || !($input['text'] instanceof Text)) {
            throw new InvalidArgumentException('The \'text\' entry should be a instance of Text');
        }

        return $input['text'];
    }
}
