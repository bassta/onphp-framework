<?php

namespace onphp\meta\builders;

use onphp\core\Exceptions\WrongStateException;
use onphp\meta\classes\MetaClass;
use onphp\meta\classes\MetaClassType;

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
final class DaoBuilder extends OnceBuilder
{
    public static function build(MetaClass $class)
    {
        $out  = self::getHead();
        $type = $class->getType();
        if ($type) {
            switch ($type->getId()) {
                case MetaClassType::CLASS_ABSTRACT:
                    $abstract = 'abstract ';
                    $notes    = 'nothing here yet';
                    break;
                case MetaClassType::CLASS_FINAL:
                    $abstract = 'final ';
                    $notes    = 'last chance for customization';
                    break;
                default:
                    throw new WrongStateException('unknown class type');
            }
        } else {
            $abstract = null;
            $notes    = 'your brilliant stuff goes here';
        }
        $out .= "{$abstract}class {$class->getName()}DAO extends Auto{$class->getName()}DAO\n{\n\t// {$notes}\n}\n";
        return $out.self::getHeel();
    }
}