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
 * A command is responsible to execute a specific task on a File's content.
 *
 * @api
 */
interface Command
{
    /**
     * @return string
     *
     * @api
     */
    public function getName();

    /**
     * @param array $input
     *
     * @api
     */
    public function execute(array $input);
}
