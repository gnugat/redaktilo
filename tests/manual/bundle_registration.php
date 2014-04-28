#!/usr/bin/env php
<?php

require_once __DIR__.'/../../vendor/autoload.php';

use Gnugat\Redaktilo\File\Filesystem;
use Gnugat\Redaktilo\Editor\LineEditor;
use Symfony\Component\Filesystem\Filesystem as FileCopier;

$sourceFilename = __DIR__.'/../fixtures/sources/AppKernel.php';
$copyFilename = __DIR__.'/../fixtures/copies/AppKernel.php';

$fileCopier = new FileCopier();
$fileCopier->copy($sourceFilename, $copyFilename, true);

echo "File before:\n";
echo file_get_contents($copyFilename);

$filesystem = new Filesystem();
$editor = new LineEditor($filesystem);

$add = '            new Gnugat\Bundle\WizardBundle\GnugatWizardBundle(),';
$after = '            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),';

$editor->openFile($copyFilename);
$editor->addAfter($add, $after);

echo "\nFile after:\n";
echo file_get_contents($copyFilename);
