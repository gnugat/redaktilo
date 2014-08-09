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

use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Search\Php\TokenBuilder;

/**
 * Converts the given file's content into PHP tokens.
 *
 * @api
 */
class PhpContentConverter implements ContentConverter
{
    /** @param TokenBuilder $tokenBuilder */
    public function __construct(TokenBuilder $tokenBuilder)
    {
        $this->tokenBuilder = $tokenBuilder;
    }

    /** {@inheritdoc} */
    public function from(File $file)
    {
        $lines = $file->getLines();
        $lineBreak = $file->getLineBreak();
        $content = implode($lineBreak, $lines);
        $rawTokens = token_get_all($content);

        return $this->tokenBuilder->buildFromRaw($rawTokens);
    }

    /** {@inheritdoc} */
    public function back(File $file, $convertedContent)
    {
        throw new \Exception("Conversion from PHP tokens to file content isn't implemented");
    }
}
