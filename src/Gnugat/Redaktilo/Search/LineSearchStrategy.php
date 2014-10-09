<?php

namespace Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\Text;

/**
 * Prepares a subset of lines for the lookup.
 */
abstract class LineSearchStrategy implements SearchStrategy
{
    /** {@inheritdoc} */
    public function findAbove(Text $text, $pattern, $location = null)
    {
        $location = (null !== $location ? $location : $text->getCurrentLineNumber());
        $lines = $text->getLines();
        $aboveLines = array_slice($lines, 0, $location, true);
        $reversedAboveLines = array_reverse($aboveLines, true);

        return $this->findIn($reversedAboveLines, $pattern);
    }

    /** {@inheritdoc} */
    public function findBelow(Text $text, $pattern, $location = null)
    {
        $location = (null !== $location ? $location : $text->getCurrentLineNumber()) + 1;
        $lines = $text->getLines();
        $belowLines = array_slice($lines, $location, null, true);

        return $this->findIn($belowLines, $pattern);
    }

    /**
     * @param array $lines
     * @param mixed $pattern
     *
     * @return mixed
     */
    abstract protected function findIn(array $lines, $pattern);
}
