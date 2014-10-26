<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo\Command\Sanitizer;

use Gnugat\Redaktilo\Command\Sanitizer\TextSanitizer;
use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Text;
use PhpSpec\ObjectBehavior;

class LocationSanitizerSpec extends ObjectBehavior
{
    const LINE_NUMBER = 42;

    function let(TextSanitizer $textSanitizer, Text $text)
    {
        $text->getCurrentLineNumber()->willReturn(self::LINE_NUMBER);
        $text->getLength()->willReturn(50);

        $this->beConstructedWith($textSanitizer);
    }
    function it_uses_current_line_if_no_location_is_given(TextSanitizer $textSanitizer, Text $text)
    {
        $input = array(
            'text' => $text
        );

        $textSanitizer->sanitize($input)->willReturn($text);
        $this->sanitize($input)->shouldReturn(self::LINE_NUMBER);
    }

    function it_fails_when_the_line_number_is_invalid(TextSanitizer $textSanitizer, Text $text)
    {
        $exception = '\Gnugat\Redaktilo\Exception\InvalidLineNumberException';

        $input = array(
            'location' => 'toto'
        );
        $textSanitizer->sanitize($input)->willReturn($text);
        $this->shouldThrow($exception)->duringSanitize($input);

        $input = array(
            'location' => -1
        );
        $textSanitizer->sanitize($input)->willReturn($text);
        $this->shouldThrow($exception)->duringSanitize($input);

        $input = array(
            'location' => 55
        );
        $textSanitizer->sanitize($input)->willReturn($text);
        $this->shouldThrow($exception)->duringSanitize($input);
    }
}
