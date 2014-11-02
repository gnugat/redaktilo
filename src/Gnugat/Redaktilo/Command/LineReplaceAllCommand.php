<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Command;

use Gnugat\Redaktilo\Command\Sanitizer\TextSanitizer;
use Gnugat\Redaktilo\Service\ContentFactory;
use Gnugat\Redaktilo\Service\TextFactory;

/**
 * Replaces all occurences of pattern in Text by given replacement.
 */
class LineReplaceAllCommand implements Command
{
    /** @var ContentFactory */
    private $contentFactory;

    /** @var TextFactory */
    private $textFactory;

    /** @var TextSanitizer */
    private $textSanitizer;

    /**
     * @param ContentFactory $contentFactory
     * @param TextFactory    $textFactory
     * @param TextSanitizer  $textSanitizer
     */
    public function __construct(
        ContentFactory $contentFactory,
        TextFactory $textFactory,
        TextSanitizer $textSanitizer
    )
    {
        $this->contentFactory = $contentFactory;
        $this->textFactory = $textFactory;
        $this->textSanitizer = $textSanitizer;
    }

    /** {@inheritDoc} */
    public function execute(array $input)
    {
        $text = $this->textSanitizer->sanitize($input);
        $pattern = $input['pattern'];
        $replacement = $input['replacement'];

        $content = $this->contentFactory->make($text);
        $replacedContent = preg_replace($pattern, $replacement, $content);
        $replacedText = $this->textFactory->make($replacedContent);
        $text->setLines($replacedText->getLines());
    }

    /** {@inheritDoc} */
    public function getName()
    {
        return 'replace_all';
    }
}
