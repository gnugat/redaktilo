<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Replace;

use Gnugat\Redaktilo\Converter\LineContentConverter;
use Gnugat\Redaktilo\File;

/**
 * This strategy manipulates lines stripped of their line break character.
 *
 * @api
 */
class LineReplaceStrategy implements ReplaceStrategy
{
    /** @var LineContentConverter */
    private $converter;

    /** @param LineContentConverter $converter */
    public function __construct(LineContentConverter $converter)
    {
        $this->converter = $converter;
    }

    /** {@inheritdoc} */
    public function supports($location)
    {
        return (is_int($location) && $location >= 0);
    }

    /** {@inheritdoc} */
    public function removeAt(File $file, $location)
    {
        $lines = $this->converter->from($file);
        unset($lines[$location]);
        $this->converter->back($file, $lines);
    }

    /** {@inheritdoc} */
    public function replaceWith(File $file, $location, $replacement)
    {
        $lines = $this->converter->from($file);
        $lines[$location] = $replacement;
        $this->converter->back($file, $lines);
    }

    /** {@inheritdoc} */
    public function insertAt(File $file, $location, $addition)
    {
        $lines = $this->converter->from($file);
        array_splice($lines, $location, 0, $addition);
        $this->converter->back($file, $lines);
    }
}
