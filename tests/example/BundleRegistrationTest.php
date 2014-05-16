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

use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class BundleRegistrationTest extends ExampleTestCase
{
    const APP_KERNEL = '%s/tests/fixtures/%s/AppKernel.php';

    private $appKernelPath;
    private $expectedAppKernelPath;

    protected function setUp()
    {
        $rootPath = __DIR__.'/../../';

        $sourceFilename = sprintf(self::APP_KERNEL, $rootPath, 'sources');
        $copyFilename = sprintf(self::APP_KERNEL, $rootPath, 'copies');
        $expectationFilename = sprintf(self::APP_KERNEL, $rootPath, 'expectations');

        $fileCopier = new SymfonyFilesystem();
        $fileCopier->copy($sourceFilename, $copyFilename, true);

        $this->appKernelPath = $copyFilename;
        $this->expectedAppKernelPath = $expectationFilename;
    }

    public function testItRegistersBundleInSymfonyApplication()
    {
        $editor = $this->makeEditor();
        $file = $editor->open($this->appKernelPath);

        $editor->jumpDownTo($file, '    public function registerBundles()');
        $editor->jumpDownTo($file, '        $bundles = array(');
        $editor->jumpDownTo($file, '        );');
        $editor->addBefore($file, '            new Gnugat\WizardBundle\GnugatWizardBundle(),');

        $editor->save($file);

        $expected = file_get_contents($this->expectedAppKernelPath);
        $actual = file_get_contents($this->appKernelPath);

        $this->assertSame($expected, $actual);
    }

    public function testItDetectsBundlePresence()
    {
        $editor = $this->makeEditor();
        $file = $editor->open($this->expectedAppKernelPath);

        $isBundlePresent = $editor->has($file, '            new Gnugat\WizardBundle\GnugatWizardBundle(),');

        $this->assertTrue($isBundlePresent);
    }

    public function testItDetectsBundleAbsence()
    {
        $editor = $this->makeEditor();
        $file = $editor->open($this->appKernelPath);

        $isBundlePresent = $editor->has($file, '            new Gnugat\WizardBundle\GnugatWizardBundle(),');

        $this->assertFalse($isBundlePresent);
    }
}
