<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Command;

use Gnugat\Redaktilo\Command\Sanitizer\TextSanitizer;
use Gnugat\Redaktilo\EditorFactory;
use Gnugat\Redaktilo\Service\ContentFactory;
use PhpSpec\ObjectBehavior;

class LineReplaceAllCommandSpec extends ObjectBehavior
{
    const PATTERN = '/snigger/';
    const REPLACEMENT = 'mocker';

    private $contentFactory;

    function let()
    {
        $this->contentFactory = new ContentFactory();
        $textSanitizer = new TextSanitizer();

        $this->beConstructedWith($this->contentFactory, $textSanitizer);
    }

    function it_is_a_command()
    {
        $this->shouldImplement('Gnugat\Redaktilo\Command\Command');
    }

    function it_replaces_all_occurences()
    {
        $editor = EditorFactory::createEditor();
        $filename = __DIR__.'/../../../../fixtures/sources/life-of-brian.txt';
        $text = $editor->open($filename);

        $expectedFilename = __DIR__.'/../../../../fixtures/expectations/life-of-brian-replace-all.txt';
        $expectedContent = file_get_contents($expectedFilename);

        $this->execute([
            'text' => $text,
            'pattern' => self::PATTERN,
            'replacement' => self::REPLACEMENT,
        ]);

        $actualContent = $this->contentFactory->make($text);
        expect($actualContent)->toBe($expectedContent);
    }
}
