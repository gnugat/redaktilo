<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Command\Sanitizer;

use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Text;
use PhpSpec\ObjectBehavior;

class TextSanitizerSpec extends ObjectBehavior
{
    function it_fails_when_no_text_is_given()
    {
        $exception = '\Gnugat\Redaktilo\Exception\InvalidArgumentException';

        $input = array();
        $this->shouldThrow($exception)->duringSanitize($input);

        $input = array(
            'replacement' => 'I\'m your father'
        );
        $this->shouldThrow($exception)->duringSanitize($input);

        $input = array(
            'text' => null
        );
        $this->shouldThrow($exception)->duringSanitize($input);
    }

    function it_returns_the_given_text_instance(Text $text, File $file)
    {
        $input = array(
            'text' => $text
        );
        $this->sanitize($input)->shouldReturn($text);

        $input = array(
            'text' => $file
        );
        $this->sanitize($input)->shouldReturn($file);
    }
}
