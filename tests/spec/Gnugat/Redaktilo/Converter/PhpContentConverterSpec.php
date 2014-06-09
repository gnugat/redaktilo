<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Converter;

use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Search\Php\Token;
use Gnugat\Redaktilo\Search\Php\TokenBuilder;
use PhpSpec\ObjectBehavior;

class PhpContentConverterSpec extends ObjectBehavior
{
    const FILENAME = '%s/tests/fixtures/sources/php-sample.php';

    function let(TokenBuilder $tokenBuilder)
    {
        $this->beConstructedWith($tokenBuilder);
    }

    function it_is_a_content_converter()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Converter\ContentConverter');
    }

    function it_converts_file_content_into_php_tokens(TokenBuilder $tokenBuilder, File $file)
    {
        $rootPath = __DIR__.'/../../../../../';
        $filename = sprintf(self::FILENAME, $rootPath);
        $content = file_get_contents($filename);
        $rawTokens = token_get_all($content);
        $file->read()->willReturn($content);

        $tokenBuilder->buildFromRaw($rawTokens)->willReturn(array());
        $this->from($file);
    }
}
