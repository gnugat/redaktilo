<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Search;

use Gnugat\Redaktilo\Search\Php\Token;
use Gnugat\Redaktilo\Service\TextToPhpConverter;
use Gnugat\Redaktilo\Text;

/**
 * Finds the given PHP token.
 *
 * @deprecated 1.7 No replacement
 */
class PhpSearchStrategy implements SearchStrategy
{
    /** @var TextToPhpConverter */
    private $converter;

    /** @param TextToPhpConverter $converter */
    public function __construct(TextToPhpConverter $converter)
    {
        $this->converter = $converter;
    }

    /** {@inheritdoc} */
    public function findAbove(Text $text, $pattern, $location = null)
    {
        trigger_error(__CLASS__.' is no longer supported and will be removed in version 2', \E_USER_DEPRECATED);
        $location = (null !== $location ? $location : $text->getCurrentLineNumber());
        $tokens = $this->converter->from($text);
        $reversedTokens = array_reverse($tokens);
        $total = count($reversedTokens);
        for ($index = 0; $index < $total; ++$index) {
            $token = $reversedTokens[$index];
            if ($token->getLineNumber() === $location) {
                break;
            }
        }

        return $this->findIn($reversedTokens, $index, $pattern);
    }

    /** {@inheritdoc} */
    public function findBelow(Text $text, $pattern, $location = null)
    {
        trigger_error(__CLASS__.' is no longer supported and will be removed in version 2', \E_USER_DEPRECATED);
        $location = (null !== $location ? $location : $text->getCurrentLineNumber()) + 1;
        $tokens = $this->converter->from($text);
        $total = count($tokens);
        for ($index = 0; $index < $total; ++$index) {
            $token = $tokens[$index];
            if ($token->getLineNumber() === $location) {
                break;
            }
        }

        return $this->findIn($tokens, $index, $pattern);
    }

    /** {@inheritdoc} */
    public function supports($pattern)
    {
        trigger_error(__CLASS__.' is no longer supported and will be removed in version 2', \E_USER_DEPRECATED);
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
     * @param int   $index
     * @param mixed $pattern
     *
     * @return mixed
     */
    private function findIn(array $collection, $index, $pattern)
    {
        $total = count($collection);
        while ($index < $total) {
            $found = $this->match($pattern, $collection, $index++);
            if (false !== $found) {
                $token = $collection[$found];

                return $token->getLineNumber();
            }
        }

        return false;
    }

    /**
     * @param int $index
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
            ++$index;
        }

        return $index - 1;
    }
}
