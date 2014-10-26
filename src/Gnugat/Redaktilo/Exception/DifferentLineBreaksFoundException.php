<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Exception;

use Gnugat\Redaktilo\Service\DifferentLineBreaksFoundException as BaseException;

/**
 * Thrown if the string given to LineBreak service contains different line breaks.
 *
 * @api
 */
class DifferentLineBreaksFoundException extends BaseException implements Exception
{
    /** @var string */
    private $string;

    /** @var int */
    private $numberLineBreakOther;

    /** @var int */
    private $numberLineBreakWindows;

    /**
     * @param string $string
     * @param int    $numberLineBreakOther
     * @param int    $numberLineBreakWindows
     */
    function __construct($string, $numberLineBreakOther, $numberLineBreakWindows)
    {
        $this->string = (string) $string;
        $this->numberLineBreakOther = (int) $numberLineBreakOther;
        $this->numberLineBreakWindows = (int) $numberLineBreakWindows;

        $message = sprintf(
            'The given string contains different line breaks,'
            .'%d LF (\'\n\', usually found on Unix/Linux systems)'
            .'and %d CR+LF (\'\r\n\', usually found on Windows systems)',
            $this->numberLineBreakOther,
            $this->numberLineBreakWindows);

        parent::__construct($message);
    }

    /** @return string */
    public function getString()
    {
        return $this->string;
    }

    /** @return int */
    public function getNumberLineBreakOther()
    {
        return $this->numberLineBreakOther;
    }

    /** @return int */
    public function getNumberLineBreakWindows()
    {
        return $this->numberLineBreakWindows;
    }
}
