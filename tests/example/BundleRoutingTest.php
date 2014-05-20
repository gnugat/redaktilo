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
use Symfony\Component\DependencyInjection\Container;
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
        $bundle = 'AcmeDemoBundle';
        $format = 'annotation';
        $prefix = '/';

        $editor = StaticContainer::makeEditor();

        $file = $editor->open($this->configPath, Filesystem::forceCreation());

        $definitionLine = $this->makeDefinitionLine($bundle, $prefix);
        $resourceLine = $this->makeResourceLine($bundle, $format);
        $typeLine = '    type: annotation';
        $prefixLine = "    prefix: $prefix";

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

    private function makeDefinitionLine($bundle, $prefix)
    {
        $unsuffixedBundleName = substr($bundle, 0, -6);
        $snakeCaseBundleName = Container::underscore($unsuffixedBundleName);
        $route = '';
        if ('/' !== $prefix) {
            $route .= str_replace('/', '_', $prefix);
        }

        return sprintf('%s:', $snakeCaseBundleName.$route);
    }

    private function makeResourceLine($bundle, $format)
    {
        if ('annotation' === $format) {
            return sprintf('    resource: "@%s/Controller/"', $bundle);
        }

        return sprintf('    resource: "@%s/Resources/config/routing.yml"', $bundle);
    }
}
