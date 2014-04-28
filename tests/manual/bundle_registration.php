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

$startBundleRegistration = '        $bundles = array(';
$endBundleRegistration = '        );';
$bundleToRegister =  '            new Gnugat\Bundle\WizardBundle\GnugatWizardBundle(),';

$editor->open($copyFilename);
$editor->jumpDownTo($startBundleRegistration);
$editor->jumpDownTo($endBundleRegistration);
$editor->addBefore($bundleToRegister);
$editor->save();

echo "\nFile after:\n";
echo file_get_contents($copyFilename);
