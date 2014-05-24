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

use Gnugat\Redaktilo\DependencyInjection\StaticContainer;
use Gnugat\Redaktilo\FactoryMethod\Filesystem;
use Gnugat\Redaktilo\FactoryMethod\Line;
use Symfony\Component\Filesystem\Exception\IOException;
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

        $symfonyFilesystem = new SymfonyFilesystem();
        if ($symfonyFilesystem->exists($copyFilename)) {
            $symfonyFilesystem->remove($copyFilename);
        }

        $this->configPath = $copyFilename;
        $this->expectedConfigPath = $expectationFilename;
    }

    public function testItAddsAnnotatedRoute()
    {
        $editor = StaticContainer::makeEditor();

        $file = $editor->open($this->configPath, Filesystem::forceCreation());

        $definitionLine = 'acme_demo:';
        $resourceLine = '    resource: "@AcmeDemoBundle/Controller/"';
        $typeLine = '    type: annotation';
        $prefixLine = '    prefix: /';

        $editor->addBefore($file, $definitionLine);
        $editor->addAfter($file, $resourceLine);
        $editor->addAfter($file, $typeLine);
        $editor->addAfter($file, $prefixLine);
        $editor->addAfter($file, Line::emptyOne());

        $editor->save($file);

        $expected = file_get_contents($this->expectedConfigPath);
        $actual = file_get_contents($this->configPath);

        $this->assertSame($expected, $actual);
    }
}
