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

use Gnugat\Redaktilo\EditorFactory;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class BundleRegistrationTest extends \PHPUnit_Framework_TestCase
{
    const APP_KERNEL = '%s/tests/fixtures/%s/AppKernel.php';

    private $appKernelPath;
    private $expectedAppKernelPath;

    private $editor;

    protected function setUp()
    {
        $rootPath = __DIR__.'/../..';

        $sourceFilename = sprintf(self::APP_KERNEL, $rootPath, 'sources');
        $copyFilename = sprintf(self::APP_KERNEL, $rootPath, 'copies');
        $expectationFilename = sprintf(self::APP_KERNEL, $rootPath, 'expectations');

        $fileCopier = new SymfonyFilesystem();
        $fileCopier->copy($sourceFilename, $copyFilename, true);

        $this->appKernelPath = $copyFilename;
        $this->expectedAppKernelPath = $expectationFilename;
        $this->editor = EditorFactory::createEditor();
    }

    public function testItRegistersBundleInSymfonyApplication()
    {
        $appKernel = $this->editor->open($this->appKernelPath);
        $this->editor->jumpBelow($appKernel, '        );');
        $this->editor->insertAbove($appKernel,'            new Gnugat\WizardBundle\GnugatWizardBundle(),');
        $this->editor->save($appKernel);

        $expected = file_get_contents($this->expectedAppKernelPath);
        $actual = file_get_contents($this->appKernelPath);

        $this->assertSame($expected, $actual);
    }

    public function testItDetectsBundlePresence()
    {
        $file = $this->editor->open($this->expectedAppKernelPath);

        $isBundlePresent = $this->editor->hasBelow($file, '            new Gnugat\WizardBundle\GnugatWizardBundle(),', 0);

        $this->assertTrue($isBundlePresent);
    }

    public function testItDetectsBundleAbsence()
    {
        $file = $this->editor->open($this->appKernelPath);

        $isBundlePresent = $this->editor->hasBelow($file, '            new Gnugat\WizardBundle\GnugatWizardBundle(),', 0);

        $this->assertFalse($isBundlePresent);
    }
}
