<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\File;

/**
 * If the pattern given to SearchEngine isn't supported by any of its registered
 * SearchStrategy.
 */
class PatternNotFoundException extends \Exception
{
    /**
     * @param File  $file
     * @param mixed $pattern
     */
    public function __construct(File $file, $pattern)
    {
        $filename = $file->getFilename();

        $messageBits[] = 'The given pattern';
        if (is_string($pattern) || is_int($pattern)) {
            $messageBits[] = sprintf('"%s"', $pattern);
        }
        $messageBits[] = sprintf('wasn\'t found in the file "%s"', $filename);

        $message = implode(' ', $messageBits);

        parent::__construct($message);
    }
}
