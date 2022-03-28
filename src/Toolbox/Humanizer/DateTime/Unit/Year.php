<?php

declare(strict_types=1);

/*
 * This file is part of the PHP Humanizer Library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Simtabi\Pheg\Toolbox\Humanizer\DateTime\Unit;

use Simtabi\Pheg\Toolbox\Humanizer\DateTime\Unit;

final class Year implements Unit
{
    public function getName() : string
    {
        return 'year';
    }

    public function getMilliseconds() : int
    {
        $day = new Day();

        return $day->getMilliseconds() * 356;
    }

    public function getDateIntervalSymbol() : string
    {
        return 'y';
    }
}
