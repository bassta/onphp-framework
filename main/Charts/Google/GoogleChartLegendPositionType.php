<?php

namespace onPHP\main\Charts\Google;

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
final class GoogleChartLegendPositionType extends Enumeration
{
    const BOTTOM = 1;
    const TOP = 2;
    const LEFT = 3;
    const RIGHT = 4;
    protected $names = array(self::BOTTOM => 'b', self::TOP => 't', self::LEFT => 'l', self::RIGHT => 'r');
    private $paramName = 'chdlp';

    /**
     * @return GoogleChartLegendPositionType
     **/
    public static function create($id)
    {
        return new self($id);
    }

    public function toString()
    {
        return $this->paramName.'='.$this->name;
    }
}