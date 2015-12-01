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
 * Inserts the given addition in the given text below the given location.
 */
class LineInsertBelowCommand implements Command
{
    /** @var TextSanitizer */
    private $textSanitizer;

    /** @var LocationSanitizer */
    private $locationSanitizer;

    /**
     * @param TextSanitizer     $textSanitizer
     * @param LocationSanitizer $locationSanitizer
     */
    public function __construct(TextSanitizer $textSanitizer, LocationSanitizer $locationSanitizer)
    {
        $this->textSanitizer = $textSanitizer;
        $this->locationSanitizer = $locationSanitizer;
    }

    /** {@inheritdoc} */
    public function execute(array $input)
    {
        $text = $this->textSanitizer->sanitize($input);
        $location = 1 + $this->locationSanitizer->sanitize($input);

        $addition = $input['addition'];

        $lines = $text->getLines();
        array_splice($lines, $location, 0, $addition);
        $text->setLines($lines);

        $text->setCurrentLineNumber($location);
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'insert_below';
    }
}
