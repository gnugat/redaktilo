<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Gnugat\Redaktilo\Editor;
use Gnugat\Redaktilo\File\Filesystem;
use Symfony\Component\Filesystem\Filesystem as FileCopier;

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

        $fileCopier = new FileCopier();
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
        $filesystem = new Filesystem();
        $editor = new Editor($filesystem);

        $editor->open($this->appKernelPath);

        $editor->jumpDownTo('    public function registerBundles()');
        $editor->jumpDownTo('        $bundles = array(');
        $editor->jumpDownTo('        );');

        $editor->addBefore($this->bundle);

        $editor->save();
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
