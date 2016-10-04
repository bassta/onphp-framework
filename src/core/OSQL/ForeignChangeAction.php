<?php

namespace onphp\core\OSQL;

use onphp\core\Base\Enumeration;

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
 * @ingroup OSQL
 **/
final class ForeignChangeAction extends Enumeration
{
    const NO_ACTION = 1;
    const RESTRICT = 2;
    const CASCADE = 3;
    const SET_NULL = 4;
    const SET_DEFAULT = 5;
    protected $names = array(self::NO_ACTION => 'NO ACTION', self::RESTRICT => 'RESTRICT', self::CASCADE => 'CASCADE', self::SET_NULL => 'SET NULL', self::SET_DEFAULT => 'SET DEFAULT');

    /**
     * @return ForeignChangeAction
     **/
    public static function noAction()
    {
        return new self(self::NO_ACTION);
    }

    /**
     * @return ForeignChangeAction
     **/
    public static function restrict()
    {
        return new self(self::RESTRICT);
    }

    /**
     * @return ForeignChangeAction
     **/
    public static function cascade()
    {
        return new self(self::CASCADE);
    }

    /**
     * @return ForeignChangeAction
     **/
    public static function setNull()
    {
        return new self(self::SET_NULL);
    }

    /**
     * @return ForeignChangeAction
     **/
    public static function setDefault()
    {
        return new self(self::SET_DEFAULT);
    }
}