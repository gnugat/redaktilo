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

use Gnugat\Redaktilo\Command\Sanitizer\LocationSanitizer;
use Gnugat\Redaktilo\Command\Sanitizer\TextSanitizer;
use Gnugat\Redaktilo\Exception\InvalidArgumentException;

/**
 * Replaces the given location in the given text with the given replacement.
 */
class LineReplaceCommand implements Command
{
    /** @var TextSanitizer */
    private $textSanitizer;

    /** @var LocationSanitizer */
    private $locationSanitizer;

    /**
     * @param TextSanitizer     $textSanitizer
     * @param LocationSanitizer $locationSanitizer
     *
     * @deprecated 1.2 input sanitizers will become mandatory from 2.0
     */
    public function __construct(TextSanitizer $textSanitizer = null, LocationSanitizer $locationSanitizer = null)
    {
        if (!$textSanitizer) {
            $textSanitizer = new TextSanitizer();
            trigger_error(__CLASS__.' now expects a text sanitizer as first argument', \E_USER_DEPRECATED);
        }
        if (!$locationSanitizer) {
            $locationSanitizer = new LocationSanitizer($textSanitizer);
            trigger_error(__CLASS__.' now expects a location sanitizer as first argument', \E_USER_DEPRECATED);
        }

        $this->textSanitizer = $textSanitizer;
        $this->locationSanitizer = $locationSanitizer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException If replacement isn't valid
     */
    public function execute(array $input)
    {
        $text = $this->textSanitizer->sanitize($input);
        $location = $this->locationSanitizer->sanitize($input);

        if (is_string($input['replacement'])) {
            // @deprecated 1.1 use $text->setLine($replacement, $location) instead
            $replacement = $input['replacement'];
        } elseif (is_callable($input['replacement'])) {
            $line = $text->getLine($location);
            $replacement = $input['replacement']($line);
        } else {
            throw new InvalidArgumentException('Invalid replacement');
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
