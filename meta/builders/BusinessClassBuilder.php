<?php

namespace onphp\meta\builders;

use onphp\meta\classes\MetaClass;
use onphp\meta\classes\MetaClassType;
use onphp\meta\patterns\AbstractClassPattern;
use onphp\meta\patterns\InternalClassPattern;
use ReflectionClass;

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
final class BusinessClassBuilder extends OnceBuilder
{
    public static function build(MetaClass $class)
    {
        $out = self::getHead();
        if ($type = $class->getType()) {
            $typeName = $type->toString().' ';
        } else {
            $typeName = null;
        }
        $interfaces = ' implements Prototyped';
        if ($class->getPattern()->daoExists() && !$class->getPattern() instanceof AbstractClassPattern) {
            $interfaces .= ', DAOConnected';
            $daoName = $class->getName().'DAO';
            $dao     = "\t/**\n\t * @return {$daoName}\n\t**/\n\tpublic static function dao()\n\t{\n\t\treturn Singleton::getInstance('{$daoName}');\n\t}\n";
        } else {
            $dao = null;
        }
        $out .= "{$typeName}class {$class->getName()} extends Auto{$class->getName()}{$interfaces}\n{";
        if (!$type || $type->getId() !== MetaClassType::CLASS_ABSTRACT) {
            $customCreate = null;
            if ($class->getFinalParent()->getPattern() instanceof InternalClassPattern) {
                $parent = $class;
                while ($parent = $parent->getParent()) {
                    $info = new ReflectionClass($parent->getName());
                    if ($info->hasMethod('create') && $info->getMethod('create')->getParameters() > 0) {
                        $customCreate = true;
                        break;
                    }
                }
            }
            if ($customCreate) {
                $creator     = $info->getMethod('create');
                $declaration = array();
                foreach ($creator->getParameters() as $parameter) {
                    $declaration[] = '$'.$parameter->getName().' = '.($parameter->getDefaultValue() ? $parameter->getDefaultValue() : 'null');
                }
                $declaration = implode(', ', $declaration);
                $out .= "\n\t/**\n\t * @return {$class->getName()}\n\t**/\n\tpublic static function create({$declaration})\n\t{\n\t\treturn new self({$declaration});\n\t}\n\t\t";
            } else {
                $out .= "\n\t/**\n\t * @return {$class->getName()}\n\t**/\n\tpublic static function create()\n\t{\n\t\treturn new self;\n\t}\n\t\t";
            }
            $protoName = 'Proto'.$class->getName();
            $out .= "\n{$dao}\n\t/**\n\t * @return {$protoName}\n\t**/\n\tpublic static function proto()\n\t{\n\t\treturn Singleton::getInstance('{$protoName}');\n\t}\n";
        }
        $out .= '
	// your brilliant stuff goes here
}
';
        return $out.self::getHeel();
    }
}