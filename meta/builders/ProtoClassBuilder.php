<?php

namespace onphp\meta\builders;

use onPHP\meta\classes\MetaClass;

/***************************************************************************
 *   Copyright (C) 2007 by Konstantin V. Arkhipov                          *
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
final class ProtoClassBuilder extends OnceBuilder
{
    public static function build(MetaClass $class)
    {
        $out = self::getHead();
        if ($type = $class->getType()) {
            $typeName = $type->toString().' ';
        } else {
            $typeName = null;
        }
        $out .= "{$typeName}class Proto{$class->getName()} extends AutoProto{$class->getName()} {/*_*/}\n";
        return $out.self::getHeel();
    }
}