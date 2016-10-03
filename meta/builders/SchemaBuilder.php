<?php

namespace onphp\meta\builders;

use onPHP\meta\classes\MetaClass;
use onPHP\meta\classes\MetaRelation;

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
final class SchemaBuilder extends BaseBuilder
{
    public static function buildTable($tableName, array $propertyList)
    {
        $out     = "\$schema->\n\taddTable(\n\t\tonPHP\\core\\OSQL\\DBTable::create('{$tableName}')->\n";
        $columns = array();
        foreach ($propertyList as $property) {
            if ($property->getRelation() && $property->getRelationId() != MetaRelation::ONE_TO_ONE) {
                continue;
            }
            $column = $property->toColumn();
            if (is_array($column)) {
                $columns = array_merge($columns, $column);
            } else {
                $columns[] = $property->toColumn();
            }
        }
        $out .= implode('->
', $columns);
        return $out.'
);

';
    }

    public static function buildRelations(MetaClass $class)
    {
        $out            = null;
        $knownJunctions = array();
        foreach ($class->getAllProperties() as $property) {
            if ($relation = $property->getRelation()) {
                $foreignClass = $property->getType()->getClass();
                if ($relation->getId() == MetaRelation::ONE_TO_MANY || !$foreignClass->getPattern()
                                                                                     ->tableExists() || $class->getParent()
                ) {
                    continue;
                } elseif ($relation->getId() == MetaRelation::MANY_TO_MANY) {
                    $tableName = $class->getTableName().'_'.$foreignClass->getTableName();
                    if (isset($knownJunctions[$tableName])) {
                        continue;
                    } else {
                        $knownJunctions[$tableName] = true;
                    }
                    $foreignPropery = clone $foreignClass->getIdentifier();
                    $name           = $class->getName();
                    $name           = strtolower($name[0]).substr($name, 1);
                    $name .= 'Id';
                    $foreignPropery->setName($name)->setColumnName($foreignPropery->getConvertedName())
                                   ->setIdentifier(false);
                    // we don't want any garbage in such tables
                    $property = clone $property;
                    $property->required();
                    // prevent name collisions
                    if ($property->getRelationColumnName() == $foreignPropery->getColumnName()) {
                        $foreignPropery->setColumnName($class->getTableName().'_'.$property->getConvertedName().'_id');
                    }
                    $out .= "\$schema->\n\taddTable(\n\t\tonPHP\\core\\OSQL\\DBTable::create('{$tableName}')->\n\t\t{$property->toColumn()}->\n\t\t{$foreignPropery->toColumn()}->\n\t\taddUniques('{$property->getRelationColumnName()}', '{$foreignPropery->getColumnName()}')\n\t);\n\n";
                } else {
                    $sourceTable  = $class->getTableName();
                    $sourceColumn = $property->getRelationColumnName();
                    $targetTable  = $foreignClass->getTableName();
                    $targetColumn = $foreignClass->getIdentifier()->getColumnName();
                    $out .= "// {$sourceTable}.{$sourceColumn} -> {$targetTable}.{$targetColumn}\n\$schema->\n\tgetTableByName('{$sourceTable}')->\n\t\tgetColumnByName('{$sourceColumn}')->\n\t\t\tsetReference(\n\t\t\t\t\$schema->\n\t\t\t\t\tgetTableByName('{$targetTable}')->\n\t\t\t\t\tgetColumnByName('{$targetColumn}'),\n\t\t\t\tForeignChangeAction::restrict(),\n\t\t\t\tForeignChangeAction::cascade()\n\t\t\t);\n\n";
                }
            }
        }
        return $out;
    }

    public static function getHead()
    {
        $out = parent::getHead();
        $out .= '$schema = new onPHP\\core\\OSQL\\DBSchema();

';
        return $out;
    }
}