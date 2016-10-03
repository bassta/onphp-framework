<?php

namespace onPHP\main\Charts\Google;

use onPHP\core\Base\Enumeration;

/***************************************************************************
 *   Copyright (C) 2009 by Denis M. Gabaidulin                             *
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
final class GoogleChartSolidFillType extends Enumeration
{
    const CHART_AREA = 1;
    const BACKGROUND = 2;
    const TRANSPARENCY = 3;
    protected $names = array(self::CHART_AREA => 'c', self::BACKGROUND => 'bg', self::TRANSPARENCY => 'a');

    /**
     * @return GoogleChartSolidFillType
     **/
    public static function create($id)
    {
        return new self($id);
    }

    public function toString()
    {
        return $this->name;
    }
}