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

use Gnugat\Redaktilo\Converter\PhpContentConverter;
use Gnugat\Redaktilo\File;
use Gnugat\Redaktilo\Search\Php\Token;

/**
 * Finds the given PHP token.
 */
class PhpSearchStrategy implements SearchStrategy
{
    /** @var PhpContentConverter */
    private $converter;

    /** @param PhpContentConverter */
    public function __construct(PhpContentConverter $converter)
    {
        $this->converter = $converter;
    }

    /** {@inheritdoc} */
    public function has(File $file, $pattern)
    {
        $tokens = $this->converter->from($file);
        $found = $this->findIn($tokens, 0, $pattern);

        return ($found !== false);
    }

    /** {@inheritdoc} */
    public function findPrevious(File $file, $pattern, $before = null)
    {
        $before = $before ?: $file->getCurrentLineNumber();
        $tokens = $this->converter->from($file);
        $reversedTokens = array_reverse($tokens);
        $total = count($reversedTokens);
        for ($index = 0; $index < $total; $index++) {
            $token = $reversedTokens[$index];
            if ($token->getLineNumber() === $before) {
                break;
            }
        }

        return $this->findIn($reversedTokens, $index, $pattern);
    }

    /** {@inheritdoc} */
    public function findNext(File $file, $pattern, $after = null)
    {
        $after = ($after ?: $file->getCurrentLineNumber()) + 1;
        $tokens = $this->converter->from($file);
        $total = count($tokens);
        for ($index = 0; $index < $total; $index++) {
            $token = $tokens[$index];
            if ($token->getLineNumber() === $after) {
                break;
            }
        }

        return $this->findIn($tokens, $index, $pattern);
    }

    /** {@inheritdoc} */
    public function supports($pattern)
    {
        if (!is_array($pattern)) {
            return false;
        }
        foreach ($pattern as $token) {
            if (!$token instanceof Token) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array   $collection
     * @param integer $index
     * @param mixed   $pattern
     *
     * @return mixed
     */
    private function findIn(array $collection, $index, $pattern)
    {
        $total = count($collection);
        while ($index < $total) {
            $found = $this->match($pattern, $collection, $index++);
            if ($found !== false) {
                $token = $collection[$found];

                return $token->getLineNumber();
            }
        }

        return false;
    }

    /**
     * @param array $wantedTokens
     * @param array $tokens
     * @param array $index
     *
     * @return mixed
     */
    private function match(array $wantedTokens, array $tokens, $index)
    {
        foreach ($wantedTokens as $wantedToken) {
            $token = $tokens[$index];
            if (!$token->isSameAs($wantedToken)) {
                return false;
            }
            $index++;
        }

        return $index - 1;
    }
}
