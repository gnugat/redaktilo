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

use Gnugat\Redaktilo\InvalidLineNumberException;

class LocationSanitizer implements InputSanitizer
{
    /** @var TextSanitizer */
    private $textSanitizer;

    /** @param TextSanitizer $textSanitizer */
    public function __construct(TextSanitizer $textSanitizer)
    {
        $this->textSanitizer = $textSanitizer;
    }

    /**
     * {@inheritdoc}
     *
     * @return int
     */
    public function sanitize(array $input)
    {
        $text = $this->textSanitizer->sanitize($input);
        $location = isset($input['location']) ? $input['location'] : null;
        if (null === $location) {
            return $text->getCurrentLineNumber();
        }
        if (!is_int($location)) {
            throw new InvalidLineNumberException($location, $text, 'The line number should be an integer');
        }
        if ($location < 0) {
            throw new InvalidLineNumberException($location, $text, 'The line number should be positive');
        }
        if ($location >= $text->getLength()) {
            throw new InvalidLineNumberException($location, $text, 'The line number should be strictly lower than the number of lines');
        }

        return $location;
    }
}
