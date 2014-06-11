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

use Gnugat\Redaktilo\Converter\LineContentConverter;

class LineReplaceCommand implements Command
{

    /** @var LineContentConverter */
    protected $converter;

    /**
     * @param LineContentConverter $converter
     */
    public function __construct(LineContentConverter $converter)
    {
        $this->converter = $converter;
    }

    /** {@inheritdoc} */
    public function execute(array $input)
    {
        $file = $input['file'];
        $location = $input['location'];
        $replacement = $input['replacement'];

        $lines = $this->converter->from($file);
        $lines[$location] = $replacement;
        $this->converter->back($file, $lines);
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'replace';
    }
}
