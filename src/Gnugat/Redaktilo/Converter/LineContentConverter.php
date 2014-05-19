<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Converter;

use Gnugat\Redaktilo\File;

/**
 * Detects the line break of the given File's content and makes an array of
 * lines stripped of it.
 *
 * @api
 */
class LineContentConverter implements ContentConverter
{
    const LINE_BREAK_OTHER = "\n";
    const LINE_BREAK_WINDOWS = "\r\n";

    /** {@inheritdoc} */
    public function from(File $file)
    {
        $content = $file->read();
        $lineBreak = $this->detectLineBreak($content);

        return explode($lineBreak, $content);
    }

    /** {@inheritdoc} */
    public function back(File $file, $convertedContent)
    {
        $content = $file->read();
        $lineBreak = $this->detectLineBreak($content);
        $file->write(implode("\n", $convertedContent));
    }

    /**
     * PHP_EOL cannot be used to guess the line break of any files: a windows
     * user (`\r\n`) can receive a file created on another OS (`\n`).
     *
     * If the given content hasn't any lines, use PHP_EOL.
     *
     * @param string $content
     *
     * @return string
     */
    public function detectLineBreak($content)
    {
        if (false === strpos($content, self::LINE_BREAK_OTHER)) {
            return PHP_EOL;
        }
        if (false !== strpos($content, self::LINE_BREAK_WINDOWS)) {
            return self::LINE_BREAK_WINDOWS;
        }

        return self::LINE_BREAK_OTHER;
    }
}
