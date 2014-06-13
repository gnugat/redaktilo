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
use Gnugat\Redaktilo\Editor;
use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Converter\LineContentConverter;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class DocumentationReformattingTest extends \PHPUnit_Framework_TestCase
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

    public function testItRemovesCommandDollarSigns()
    {
        $editor = EditorFactory::createEditor();
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
        $converter = new LineContentConverter();

        $editor->jumpDownTo($file, '/.. code-block:: bash/');
        $lines = $converter->from($file);
        $editor->jumpDownTo($file, 1);

        while ((bool) preg_match('/^   / ', $line = $lines[$file->getCurrentLineNumber()]) || '' === $line) {
            if ('' !== $line) {
                $replacement = preg_replace('/\$ /', '', $line);
                $editor->changeTo($file, $replacement);
            }
            $editor->jumpDownTo($file, 1);
        }

        $this->removeDollars($editor, $file);
    }
}
