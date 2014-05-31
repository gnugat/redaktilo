<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo;

class EditorFactory
{
    public static function createBuilder()
    {
        return new EditorBuilder();
    }

    public static function createEditor()
    {
        return self::createBuilder()->getEditor();
    }
}
