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
use Gnugat\Redaktilo\Filesystem;
use Gnugat\Redaktilo\Search\SearchEngine;
use Gnugat\Redaktilo\Search\LineNumberSearchStrategy;
use Gnugat\Redaktilo\Search\LineSearchStrategy;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class ExampleTestCase extends \PHPUnit_Framework_TestCase
{
    protected function makeEditor()
    {
        $searchEngine = new SearchEngine();

        $lineSearchStrategy = new LineSearchStrategy();
        $searchEngine->registerStrategy($lineSearchStrategy);

        $lineNumberSearchStrategy = new LineNumberSearchStrategy();
        $searchEngine->registerStrategy($lineNumberSearchStrategy);

        $symfonyFilesystem = new SymfonyFilesystem();
        $filesystem = new Filesystem($symfonyFilesystem);
        $editor = new Editor($filesystem, $searchEngine);

        return $editor;
    }
}
