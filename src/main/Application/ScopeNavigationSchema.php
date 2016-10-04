<?php

namespace onphp\main\Application;

use onphp\core\Base\Singleton;

/***************************************************************************
 *   Copyright (C) 2007 by Ivan Y. Khvostishkov                            *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
abstract class ScopeNavigationSchema extends Singleton
{
    public abstract function extractPath(&$scope);

    public abstract function getScope($path);
}