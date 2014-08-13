<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sensio\Bundle\GeneratorBundle\Manipulator;

use Gnugat\Redaktilo\EditorFactory;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class RoutingManipulator extends \PHPUnit_Framework_TestCase
{
    const CONFIG = '%s/tests/fixtures/%s/routing.yml';

    private $configPath;
    private $expectedConfigPath;

    protected function setUp()
    {
        $rootPath = __DIR__.'/../..';

        $sourceFilename = sprintf(self::CONFIG, $rootPath, 'sources');
        $copyFilename = sprintf(self::CONFIG, $rootPath, 'copies');
        $expectationFilename = sprintf(self::CONFIG, $rootPath, 'expectations');

        $fileCopier = new SymfonyFilesystem();
        $fileCopier->copy($sourceFilename, $copyFilename, true);

        $this->configPath = $copyFilename;
        $this->expectedConfigPath = $expectationFilename;
    }

    public function testItAddsAnnotatedRoute()
    {
        $editor = EditorFactory::createEditor();

        $file = $editor->openFile($this->configPath, true);

        $definitionLine = 'acme_demo:';
        $resourceLine   = '    resource: "@AcmeDemoBundle/Controller/"';
        $typeLine       = '    type: annotation';
        $prefixLine     = '    prefix: /';
        $emptyLine      = '';

        $editor->insertAbove($file, $definitionLine);
        $editor->insertUnder($file, $resourceLine);
        $editor->insertUnder($file, $typeLine);
        $editor->insertUnder($file, $prefixLine);
        $editor->insertUnder($file, $emptyLine);

        $editor->saveFile($file);

        $expected = file_get_contents($this->expectedConfigPath);
        $actual = file_get_contents($this->configPath);

        $this->assertSame($expected, $actual);
    }
}
