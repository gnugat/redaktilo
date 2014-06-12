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
    public function has(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $found = $this->findIn($lines, $pattern);

        return (false !== $found);
    }

    /** {@inheritdoc} */
    public function findNext(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $currentLineNumber = $file->getCurrentLineNumber() + 1;
        $nextLines = array_slice($lines, $currentLineNumber, null, true);

        return $this->findIn($nextLines, $pattern);
    }

    /** {@inheritdoc} */
    public function findPrevious(File $file, $pattern)
    {
        $lines = $this->converter->from($file);
        $currentLineNumber = $file->getCurrentLineNumber() - 1;
        $previousLines = array_slice($lines, 0, $currentLineNumber, true);
        $reversedPreviousLines = array_reverse($previousLines, true);

        return $this->findIn($reversedPreviousLines, $pattern);
    }

    /**
     * @param array $lines
     * @param mixed $pattern
     *
     * @return mixed
     */
    abstract protected function findIn(array $lines, $pattern);
}
