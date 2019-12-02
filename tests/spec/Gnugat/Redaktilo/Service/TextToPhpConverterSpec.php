<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Service;

use Gnugat\Redaktilo\Search\Php\TokenBuilder;
use Gnugat\Redaktilo\Text;
use Gnugat\Redaktilo\Util\StringUtil;
use PhpSpec\ObjectBehavior;

class TextToPhpConverterSpec extends ObjectBehavior
{
    const FILENAME = '%s/tests/fixtures/sources/php-sample.php';

    function let(TokenBuilder $tokenBuilder)
    {
        $this->beConstructedWith($tokenBuilder);
    }

    function it_converts_file_content_into_php_tokens(TokenBuilder $tokenBuilder, Text $text)
    {
        $rootPath = __DIR__.'/../../../../../';
        $filename = sprintf(self::FILENAME, $rootPath);
        $content = file_get_contents($filename);
        $lineBreak = StringUtil::detectLineBreak($content);
        $lines = explode($lineBreak, $content);
        $rawTokens = token_get_all($content);
        $text->getLineBreak()->willReturn($lineBreak);
        $text->getLines()->willReturn($lines);

        $tokenBuilder->buildFromRaw($rawTokens)->willReturn([]);
        $this->from($text);
    }
}
