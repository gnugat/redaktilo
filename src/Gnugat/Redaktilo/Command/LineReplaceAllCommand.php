<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Faugeron <faugeron.loic@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Command;

use Gnugat\Redaktilo\Command\Sanitizer\TextSanitizer;
use Gnugat\Redaktilo\Service\ContentFactory;
use Gnugat\Redaktilo\Service\TextFactory;
use Gnugat\Redaktilo\Text;

/**
 * Replaces all occurences of pattern in Text by given replacement.
 */
class LineReplaceAllCommand implements Command
{
    /** @var ContentFactory */
    private $contentFactory;

    /** @var TextSanitizer */
    private $textSanitizer;

    /**
     * @param TextFactory $textFactory
     */
    public function __construct(ContentFactory $contentFactory, TextSanitizer $textSanitizer)
    {
        $this->contentFactory = $contentFactory;
        $this->textSanitizer = $textSanitizer;
    }

    /** {@inheritdoc} */
    public function execute(array $input)
    {
        $text = $this->textSanitizer->sanitize($input);
        $pattern = $input['pattern'];
        $replacement = $input['replacement'];

        $content = $this->contentFactory->make($text);
        $replacedContent = preg_replace($pattern, $replacement, $content);
        $replacedText = Text::fromString($replacedContent);
        $text->setLines($replacedText->getLines());
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'replace_all';
    }
}
