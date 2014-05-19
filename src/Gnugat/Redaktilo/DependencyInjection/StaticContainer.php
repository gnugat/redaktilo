<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\DependencyInjection;

use Gnugat\Redaktilo\Editor;
use Gnugat\Redaktilo\Engine\ReplaceEngine;
use Gnugat\Redaktilo\Engine\SearchEngine;
use Gnugat\Redaktilo\Filesystem;
use Gnugat\Redaktilo\Replace\LineReplaceStrategy;
use Gnugat\Redaktilo\Search\LineNumberSearchStrategy;
use Gnugat\Redaktilo\Search\LineSearchStrategy;
use Symfony\Component\Filesystem\Filesystem as SfFilesystem;

/**
 * A convenient facade which creates instances, so you don't have to.
 *
 * @api
 */
class StaticContainer
{
    /** @var Editor */
    private static $editor;

    /** @var Filesystem */
    private static $filesystem;

    /** @var ReplaceEngine */
    private static $replaceEngine;

    /** @var LineReplaceStrategy */
    private static $lineReplaceStrategy;

    /** @var SearchEngine */
    private static $searchEngine;

    /** @var LineSearchStrategy */
    private static $lineSearchStrategy;

    /** @var LineNumberSearchStrategy */
    private static $lineNumberSearchStrategy;

    /** @return Editor */
    public static function makeEditor()
    {
        if (null !== self::$editor) {
            return self::$editor;
        }
        $filesystem = self::makeFilesystem();
        $replaceEngine = self::makeReplaceEngine();
        $searchEngine = self::makeSearchEngine();
        self::$editor = new Editor(
            $filesystem,
            $searchEngine,
            $replaceEngine
        );

        return self::$editor;
    }

    /** @return Filesystem */
    public static function makeFilesystem()
    {
        if (null !== self::$filesystem) {
            return self::$filesystem;
        }
        $sfFilesystem = new SfFilesystem();
        self::$filesystem = new Filesystem($sfFilesystem);

        return self::$filesystem;
    }

    /** @return ReplaceEngine */
    public static function makeReplaceEngine()
    {
        if (null !== self::$replaceEngine) {
            return self::$replaceEngine;
        }
        $lineReplaceStrategy = self::makeLineReplaceStrategy();
        self::$replaceEngine = new ReplaceEngine();
        self::$replaceEngine->registerStrategy($lineReplaceStrategy);

        return self::$replaceEngine;
    }

    /** @return LineReplaceStrategy */
    public static function makeLineReplaceStrategy()
    {
        if (null !== self::$lineReplaceStrategy) {
            return self::$lineReplaceStrategy;
        }
        self::$lineReplaceStrategy = new LineReplaceStrategy();

        return self::$lineReplaceStrategy;
    }

    /** @return SearchEngine */
    public static function makeSearchEngine()
    {
        if (null !== self::$searchEngine) {
            return self::$searchEngine;
        }
        $lineSearchStrategy = self::makeLineSearchStrategy();
        $lineNumberSearchStrategy = self::makeLineNumberSearchStrategy();
        self::$searchEngine = new SearchEngine();
        self::$searchEngine->registerStrategy($lineSearchStrategy);
        self::$searchEngine->registerStrategy($lineNumberSearchStrategy);

        return self::$searchEngine;
    }

    /** @return LineSearchStrategy */
    public static function makeLineSearchStrategy()
    {
        if (null !== self::$lineSearchStrategy) {
            return self::$lineSearchStrategy;
        }
        self::$lineSearchStrategy = new LineSearchStrategy();

        return self::$lineSearchStrategy;
    }

    /** @return LineNumberSearchStrategy */
    public static function makeLineNumberSearchStrategy()
    {
        if (null !== self::$lineNumberSearchStrategy) {
            return self::$lineNumberSearchStrategy;
        }
        self::$lineNumberSearchStrategy = new LineNumberSearchStrategy();

        return self::$lineNumberSearchStrategy;
    }
}
