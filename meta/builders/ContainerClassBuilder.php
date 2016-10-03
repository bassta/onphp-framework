<?php

namespace onphp\meta\builders;

use onPHP\core\Exceptions\UnsupportedMethodException;
use onPHP\meta\classes\MetaClass;
use onPHP\meta\classes\MetaClassProperty;
use onPHP\meta\classes\MetaRelation;

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
final class ContainerClassBuilder extends OnceBuilder
{
    public static function build(MetaClass $class)
    {
        throw new UnsupportedMethodException();
    }

    public static function buildContainer(MetaClass $class, MetaClassProperty $holder)
    {
        $out           = self::getHead();
        $containerName = $class->getName().ucfirst($holder->getName()).'DAO';
        $out .= 'final class '.$containerName.' extends '.$holder->getRelation()->toString().'Linked'.'
{
';
        $className        = $class->getName();
        $propertyName     = strtolower($className[0]).substr($className, 1);
        $remoteColumnName = $holder->getType()->getClass()->getTableName();
        $out .= "public function __construct({$className} \${$propertyName}, \$lazy = false)\n{\n\tparent::__construct(\n\t\t\${$propertyName},\n\t\t{$holder->getType()->getClassName()}::dao(),\n\t\t\$lazy\n\t);\n}\n\n/**\n * @return {$containerName}\n**/\npublic static function create({$className} \${$propertyName}, \$lazy = false)\n{\n\treturn new self(\${$propertyName}, \$lazy);\n}\n";
        if ($holder->getRelation()->getId() == MetaRelation::MANY_TO_MANY) {
            $out .= "\npublic function getHelperTable()\n{\n\treturn '{$class->getTableName()}_{$remoteColumnName}';\n}\n\npublic function getChildIdField()\n{\n\treturn '{$remoteColumnName}_id';\n}\n";
        }
        $out .= "\npublic function getParentIdField()\n{\n\treturn '{$class->getTableName()}_id';\n}\n";
        $out .= '}
';
        $out .= self::getHeel();
        return $out;
    }
}