<?php

namespace onPHP\core\Cache;

use onPHP\core\Base\Singleton;

/***************************************************************************
 *   Copyright (C) 2005-2007 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

/**
 * @ingroup Lockers
 **/
abstract class BaseLocker extends Singleton
{
    protected $pool = array();

    /// acquire lock
    public abstract function get($key);

    /// release lock
    public abstract function free($key);

    /// completely remove lock

    public function clean()
    {
        foreach (array_keys($this->pool) as $key) {
            $this->drop($key);
        }
        return true;
    }

    /// drop all acquired/released locks

    public abstract function drop($key);
}