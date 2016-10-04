<?php

namespace onphp\meta\builders;

use onphp\meta\classes\MetaClass;

/***************************************************************************
 *   Copyright (C) 2006-2008 by Konstantin V. Arkhipov                     *
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
final class AutoProtoClassBuilder extends BaseBuilder
{
    public static function build(MetaClass $class)
    {
        $out    = self::getHead();
        $parent = $class->getParent();
        if ($class->hasBuildableParent()) {
            $parentName = 'Proto'.$parent->getName();
        } else {
            $parentName = 'onPHP\\main\\Base\\AbstractProtoClass';
        }
        $out .= "abstract class AutoProto{$class->getName()} extends {$parentName}\n{";
        $classDump = self::dumpMetaClass($class);
        $out .= "\n{$classDump}\n}\n";
        return $out.self::getHeel();
    }

    private static function dumpMetaClass(MetaClass $class)
    {
        $propertyList = $class->getWithInternalProperties();
        $out          = '	protected function makePropertyList()
	{
';
        if ($class->hasBuildableParent()) {
            $out .= '		return
			array_merge(
				parent::makePropertyList(),
				array(
';
            if ($class->getIdentifier()) {
                $propertyList[$class->getIdentifier()->getName()] = $class->getIdentifier();
            }
        } else {
            $out .= '		return array(
';
        }
        $list = array();
        foreach ($propertyList as $property) {
            $list[] = "'{$property->getName()}' => ".$property->toLightProperty($class)->toString();
        }
        $out .= implode(',
', $list);
        if ($class->hasBuildableParent()) {
            $out .= '
)';
        }
        $out .= '
		);
	}';
        return $out;
    }
}