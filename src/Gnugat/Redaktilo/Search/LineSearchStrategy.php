<?php

namespace Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\File;

/**
 * Prepares a subset of lines for the lookup.
 */
abstract class LineSearchStrategy implements SearchStrategy
{
    /** @var \Gnugat\Redaktilo\Converter\ContentConverter */
    protected $converter;

    /** {@inheritdoc} */
    public function findPrevious(File $file, $pattern, $before = null)
    {
        $before = ($before ?: $file->getCurrentLineNumber()) - 1;
        $lines = $this->converter->from($file);
        $previousLines = array_slice($lines, 0, $before, true);
        $reversedPreviousLines = array_reverse($previousLines, true);

        return $this->findIn($reversedPreviousLines, $pattern);
    }

    /** {@inheritdoc} */
    public function findNext(File $file, $pattern, $after = null)
    {
        $after = ($after ?: $file->getCurrentLineNumber()) + 1;
        $lines = $this->converter->from($file);
        $nextLines = array_slice($lines, $after, null, true);

        return $this->findIn($nextLines, $pattern);
    }

    /**
     * @param array $lines
     * @param mixed $pattern
     *
     * @return mixed
     */
    abstract protected function findIn(array $lines, $pattern);
}
