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

/**
 * Executes a task with the given input.
 *
 * @api
 */
interface Command
{
    /** @return string */
    public function getName();

    /** @param array $input */
    public function execute(array $input);
}
