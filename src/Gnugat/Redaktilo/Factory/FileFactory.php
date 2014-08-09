<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Factory;

use Gnugat\Redaktilo\Converter\LineContentConverter;
use Gnugat\Redaktilo\File;

/**
 * Stateless service which creates a File from the given filename.
 */
class FileFactory
{
    /** @var LineContentConverter */
    private $lineContentConverter;

    /** @param LineContentConverter $lineContentConverter */
    public function __construct(LineContentConverter $lineContentConverter)
    {
        $this->lineContentConverter = $lineContentConverter;
    }

    /**
     * @param string $content
     *
     * @return Text
     */
    public function make($filename, $content)
    {
        $lineBreak = $this->lineContentConverter->detectLineBreak($content);
        $lines = explode($lineBreak, $content);

        return new File($filename, $lines, $lineBreak);
    }
}
