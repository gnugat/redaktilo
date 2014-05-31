<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Redaktilo;

use PhpSpec\ObjectBehavior;

class EditorFactorySpec extends ObjectBehavior
{
    function it_creates_default_editor_instances()
    {
        $this->createEditor()->shouldReturnAnInstanceOf('Gnugat\Redaktilo\Editor');
    }

    function it_creates_builders()
    {
        $this->createBuilder()->shouldReturnAnInstanceOf('Gnugat\Redaktilo\EditorBuilder');
    }
}
