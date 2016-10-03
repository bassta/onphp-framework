<?php

namespace onphp\main\Charts\Google;

use onPHP\core\Base\Stringable;

/***************************************************************************
 *   Copyright (C) 2008 by Denis M. Gabaidulin                             *
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
abstract class BaseGoogleChartParameter implements Stringable
{
    protected $name = null;

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return BaseGoogleChartParameter
     **/
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}