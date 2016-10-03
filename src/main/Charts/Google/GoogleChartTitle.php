<?php

namespace onphp\main\Charts\Google;

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
final class GoogleChartTitle extends BaseGoogleChartParameter
{
    protected static $paramName = 'chtt';
    private $title = null;

    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * @return GoogleChartTitle
     **/
    public static function create($title)
    {
        return new self($title);
    }

    public function toString()
    {
        return 'chtt='.$this->title;
    }
}