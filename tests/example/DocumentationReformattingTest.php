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
use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Search\FactoryMethod\LineNumber;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class DocumentationReformattingTest extends ExampleTestCase
{
    const APP_KERNEL = '%s/tests/fixtures/%s/doctrine.rst';

    private $originalPath;
    private $expectedPath;

    protected function setUp()
    {
        $rootPath = __DIR__.'/../..';

        $sourceFilename = sprintf(self::APP_KERNEL, $rootPath, 'sources');
        $copyFilename = sprintf(self::APP_KERNEL, $rootPath, 'copies');
        $expectationFilename = sprintf(self::APP_KERNEL, $rootPath, 'expectations');

        $fileCopier = new SymfonyFilesystem();
        $fileCopier->copy($sourceFilename, $copyFilename, true);

        $this->originalPath = $copyFilename;
        $this->expectedPath = $expectationFilename;
    }

    public function testItRegistersBundleInSymfonyApplication()
    {
        $editor = $this->makeEditor();
        $file = $editor->open($this->originalPath);

        try {
            $this->removeDollars($editor, $file);
        } catch (\Exception $e) {
        }

        $editor->save($file);

        $expected = file_get_contents($this->expectedPath);
        $actual = file_get_contents($this->originalPath);

        $this->assertSame($expected, $actual);
    }

    protected function removeDollars(Editor $editor, File $file)
    {
        $editor->jumpDownTo($file, '.. code-block:: bash');
        $lines = $file->readlines();
        $editor->jumpDownTo($file, LineNumber::down(1));

        while ((bool) preg_match('/^   / ', $line = $lines[$file->getCurrentLineNumber()]) || '' === $line) {
            if ('' !== $line) {
                $editor->replaceWith($file, '/\$ /', '');
            }

            $editor->jumpDownTo($file, LineNumber::down(1));
        }

        $this->removeDollars($editor, $file);
    }
}
