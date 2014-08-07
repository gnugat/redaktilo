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
    public function findAbove(File $file, $pattern, $before = null)
    {
        $before = ($before ?: $file->getCurrentLineNumber()) - 1;
        $lines = $this->converter->from($file);
        $aboveLines = array_slice($lines, 0, $before, true);
        $reversedAboveLines = array_reverse($aboveLines, true);

        return $this->findIn($reversedAboveLines, $pattern);
    }

    /** {@inheritdoc} */
    public function findUnder(File $file, $pattern, $after = null)
    {
        $after = ($after ?: $file->getCurrentLineNumber()) + 1;
        $lines = $this->converter->from($file);
        $underLines = array_slice($lines, $after, null, true);

        return $this->findIn($underLines, $pattern);
    }

    /**
     * @param array $lines
     * @param mixed $pattern
     *
     * @return mixed
     */
    abstract protected function findIn(array $lines, $pattern);
}
