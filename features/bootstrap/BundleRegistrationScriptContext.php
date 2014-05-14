<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Gnugat\Redaktilo\Editor;
use Gnugat\Redaktilo\Filesystem;
use Gnugat\Redaktilo\Search\SearchEngine;
use Gnugat\Redaktilo\Search\LineSearchStrategy;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class BundleRegistrationScriptContext implements SnippetAcceptingContext
{
    const APP_KERNEL = '%s/tests/fixtures/%s/AppKernel.php';
    const BUNDLE = 'Gnugat\WizardBundle\GnugatWizardBundle';

    private $appKernelPath;
    private $bundle;

    /**
     * @Given a Symfony2 application
     */
    public function aSymfonyApplication()
    {
        $rootPath = __DIR__.'/../../';

        $sourceFilename = sprintf(self::APP_KERNEL, $rootPath, 'sources');
        $copyFilename = sprintf(self::APP_KERNEL, $rootPath, 'copies');

        $fileCopier = new SymfonyFilesystem();
        $fileCopier->copy($sourceFilename, $copyFilename, true);

        $this->appKernelPath = $copyFilename;
    }

    /**
     * @Given a bundle's fully qualified classname
     */
    public function aBundleFullyQualifiedClassname()
    {
        $this->bundle = sprintf('            new %s(),', self::BUNDLE);
    }

    /**
     * @When I insert it in the application's kernel
     */
    public function iInsertItInTheApplicationKernel()
    {
        $lineSearchStrategy = new LineSearchStrategy();
        $searchEngine = new SearchEngine();
        $searchEngine->registerStrategy($lineSearchStrategy);

        $symfonyFilesystem = new SymfonyFilesystem();
        $filesystem = new Filesystem($symfonyFilesystem);
        $editor = new Editor($filesystem, $searchEngine);

        $file = $editor->open($this->appKernelPath);

        $editor->jumpDownTo($file, '    public function registerBundles()');
        $editor->jumpDownTo($file, '        $bundles = array(');
        $editor->jumpDownTo($file, '        );');

        $editor->addBefore($file, $this->bundle);

        $editor->save($file);
    }

    /**
     * @Then the bundle should be registered
     */
    public function theBundleShouldBeRegistered()
    {
        $rootPath = __DIR__.'/../../';

        $expectedAppKernel = sprintf(self::APP_KERNEL, $rootPath, 'expectations');

        $expected = file_get_contents($expectedAppKernel);
        $actual = file_get_contents($this->appKernelPath);

        PHPUnit_Framework_Assert::assertSame($expected, $actual);
    }
}
