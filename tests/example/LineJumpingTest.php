<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace example\Gnugat\Redaktilo;

use Gnugat\Redaktilo\EditorFactory;
use Gnugat\Redaktilo\Text;

class LineJumpingTest extends \PHPUnit_Framework_TestCase
{
    private $editor;

    protected function setUp()
    {
        $this->editor = EditorFactory::createEditor();
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new \RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
        });
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Gnugat\Redaktilo\Search\LineNumberSearchStrategy has been replaced by Text#setCurrentLineNumber on line 50 in file /home/gnucat/Projects/gnugat/redaktilo/src/Gnugat/Redaktilo/Search/LineNumberSearchStrategy.php
     */
    public function testItCannotFindNonExistingLine()
    {
        $text = Text::fromArray(array(''));
        $this->editor->jumpBelow($text, 0);
    }

    protected function tearDown()
    {
        restore_error_handler();
    }
}
