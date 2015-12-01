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

use Gnugat\Redaktilo\EditorFactory;
use PhpSpec\ObjectBehavior;

class ContentFactorySpec extends ObjectBehavior
{
    private $editor;

    function let()
    {
        $this->editor = EditorFactory::createEditor();
    }

    function it_creates_content_from_text()
    {
        $filename = __DIR__.'/../../../../fixtures/sources/life-of-brian.txt';
        $expectedContent = file_get_contents($filename);
        $text = $this->editor->open($filename);

        $this->make($text)->shouldBe($expectedContent);
    }
}
