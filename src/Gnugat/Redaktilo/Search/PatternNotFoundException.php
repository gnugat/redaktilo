<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\File;

/**
 * Thrown if the pattern given to the SearchEngine couldn't match anything in
 * the Text.
 *
 * @api
 */
class PatternNotFoundException extends \Exception
{
    /** @var mixed */
    private $pattern;

    /** @var mixed */
    private $text;

    /**
     * @param mixed $pattern
     * @param mixed $text
     */
    public function __construct($pattern, $text)
    {
        $this->pattern = $pattern;
        $this->text = $text;

        $patternMessage = 'given pattern';
        if (is_string($pattern) || is_int($pattern)) {
            $patternMessage .= ' "'.strval($pattern).'"';
        }
        $textMessage = 'the given text';
        if ($text instanceof File) {
            $textMessage = 'the given file '.$text->getFilename();
        }

        $message = sprintf('The %s couldn\'t be find in %s', $patternMessage, $textMessage);

        parent::__construct($message);
    }

    /** @return mixed */
    public function getPattern()
    {
        return $this->pattern;
    }

    /** @return mixed */
    public function getText()
    {
        return $this->text;
    }
}
