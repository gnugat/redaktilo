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

use Gnugat\Redaktilo\Text;
use Gnugat\Redaktilo\Search\Php\TokenBuilder;

/**
 * Converts the given text's content into PHP tokens.
 */
class TextToPhpConverter
{
    /** @param TokenBuilder $tokenBuilder */
    public function __construct(TokenBuilder $tokenBuilder)
    {
        $this->tokenBuilder = $tokenBuilder;
    }

    /**
     * @param Text $text
     *
     * @return array
     */
    public function from(Text $text)
    {
        $lines = $text->getLines();
        $lineBreak = $text->getLineBreak();
        $content = implode($lineBreak, $lines);
        $rawTokens = token_get_all($content);

        return $this->tokenBuilder->buildFromRaw($rawTokens);
    }
}
