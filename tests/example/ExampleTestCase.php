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

use Gnugat\Redaktilo\Editor;
use Gnugat\Redaktilo\Engine\ReplaceEngine;
use Gnugat\Redaktilo\Engine\SearchEngine;
use Gnugat\Redaktilo\Filesystem;
use Gnugat\Redaktilo\Replace\LineReplaceStrategy;
use Gnugat\Redaktilo\Search\LineNumberSearchStrategy;
use Gnugat\Redaktilo\Search\LineSearchStrategy;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class ExampleTestCase extends \PHPUnit_Framework_TestCase
{
    protected function makeEditor()
    {
        $searchEngine = $this->makeSearchEngine();
        $replaceEngine = $this->makeReplaceEngine();

        $symfonyFilesystem = new SymfonyFilesystem();
        $filesystem = new Filesystem($symfonyFilesystem);
        $editor = new Editor(
            $filesystem,
            $searchEngine,
            $replaceEngine
        );

        return $editor;
    }

    private function makeSearchEngine()
    {
        $searchEngine = new SearchEngine();

        $lineSearchStrategy = new LineSearchStrategy();
        $searchEngine->registerStrategy($lineSearchStrategy);

        $lineNumberSearchStrategy = new LineNumberSearchStrategy();
        $searchEngine->registerStrategy($lineNumberSearchStrategy);

        return $searchEngine;
    }

    private function makeReplaceEngine()
    {
        $replaceEngine = new ReplaceEngine();

        $lineNumberReplaceStrategy = new LineReplaceStrategy();
        $replaceEngine->registerStrategy($lineNumberReplaceStrategy);

        return $replaceEngine;
    }
}
