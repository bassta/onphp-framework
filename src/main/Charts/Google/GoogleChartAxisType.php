<?php

namespace onphp\main\Charts\Google;

use onPHP\core\Base\Enumeration;

/***************************************************************************
 *   Copyright (C) 2008-2009 by Denis M. Gabaidulin                        *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

/**
 * @ingroup GoogleChart
 **/
final class GoogleChartAxisType extends Enumeration
{
    const X = 1;
    const Y = 2;
    const R = 3;
    // aka right y
    private static $paramName = 'chxt';
    protected $names = array(self::X => 'x', self::Y => 'y', self::R => 'r');

    public static function getParamName()
    {
        return self::$paramName;
    }

    public function toString()
    {
        return $this->name;
    }
}