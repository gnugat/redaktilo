<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Command;

use Gnugat\Redaktilo\Command\Sanitizer\LocationSanitizer;
use Gnugat\Redaktilo\Command\Sanitizer\TextSanitizer;

/**
 * Removes the given location in the given text.
 */
class LineRemoveCommand implements Command
{
    /** @var TextSanitizer */
    private $textSanitizer;

    /** @var LocationSanitizer */
    private $locationSanitizer;

    public function __construct(TextSanitizer $textSanitizer, LocationSanitizer $locationSanitizer)
    {
        $this->textSanitizer = $textSanitizer;
        $this->locationSanitizer = $locationSanitizer;
    }

    /** {@inheritdoc} */
    public function execute(array $input)
    {
        $text = $this->textSanitizer->sanitize($input);
        $location = $this->locationSanitizer->sanitize($input);

        $lines = $text->getLines();
        array_splice($lines, $location, 1);
        $text->setLines($lines);

        $lineNumber = ($location === $text->getLength()) ? $location - 1 : $location;
        $text->setCurrentLineNumber($lineNumber);
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'remove';
    }
}
