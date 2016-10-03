<?php

namespace onPHP\core\Form\Filters;

use onPHP\core\Base\Singleton;

/***************************************************************************
 *   Copyright (C) 2006-2007 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

/**
 * @ingroup Filters
 **/
final class UrlDecodeFilter extends BaseFilter
{
    /**
     * @return UrlDecodeFilter
     **/
    public static function me()
    {
        return Singleton::getInstance(__CLASS__);
    }

    public function apply($value)
    {
        return urldecode($value);
    }
}