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

use Gnugat\Redaktilo\Service\EditorBuilder;

/**
 * @author Wouter J <wouter@wouterj.nl>
 *
 * @api
 */
class EditorFactory
{
    /**
     * @return EditorBuilder
     *
     * @api
     */
    public static function createBuilder()
    {
        return new EditorBuilder();
    }

    /**
     * @return Editor
     *
     * @api
     */
    public static function createEditor()
    {
        return self::createBuilder()->getEditor();
    }
}
