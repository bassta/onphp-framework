<?php

namespace onphp\meta\builders;

use onPHP\meta\classes\MetaClass;

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
 * @ingroup Builders
 **/
final class DictionaryDaoBuilder extends BaseBuilder
{
    public static function build(MetaClass $class)
    {
        $out = self::getHead();
        $out .= "abstract class Auto{$class->getName()}DAO extends StorableDAO\n{\n";
        $pointers = self::buildPointers($class);
        $out .= "{$pointers}\n}\n";
        return $out.self::getHeel();
    }
}