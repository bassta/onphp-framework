<?php

namespace onphp\meta\types;

use onPHP\main\Criteria\FetchStrategy;
use onPHP\meta\classes\MetaClass;
use onPHP\meta\classes\MetaClassProperty;
use onPHP\meta\classes\MetaConfiguration;
use onPHP\meta\classes\MetaRelation;
use onPHP\meta\patterns\EnumClassPattern;
use onPHP\meta\patterns\EnumerationClassPattern;

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
 * @ingroup Types
 **/
class ObjectType extends BasePropertyType
{
    private $className = null;

    public function getPrimitiveName()
    {
        return 'identifier';
    }

    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @return MetaClass
     **/
    public function getClass()
    {
        return MetaConfiguration::me()->getClassByName($this->className);
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getDeclaration()
    {
        return 'null';
    }

    public function isGeneric()
    {
        return false;
    }

    public function isMeasurable()
    {
        return false;
    }

    public function toMethods(MetaClass $class, MetaClassProperty $property, MetaClassProperty $holder = null)
    {
        return parent::toMethods($class, $property, $holder).$this->toDropper($class, $property, $holder);
    }

    public function toGetter(MetaClass $class, MetaClassProperty $property, MetaClassProperty $holder = null)
    {
        $name       = $property->getName();
        $methodName = 'get'.ucfirst($property->getName());
        $classHint  = $property->getType()->getHint();
        if ($holder) {
            if ($property->getType() instanceof ObjectType) {
                $class = $property->getType()->getClassName();
            } else {
                $class = null;
            }
            return "\n/**\n * @return {$class}\n**/\npublic function {$methodName}()\n{\n\treturn \$this->{$holder->getName()}->{$methodName}();\n}\n";
        } else {
            if ($property->getFetchStrategyId() == FetchStrategy::LAZY) {
                $className         = $property->getType()->getClassName();
                $isEnumeration     = $property->getType()->getClass()
                                              ->getPattern() instanceof EnumerationClassPattern || $property->getType()
                                                                                                            ->getClass()
                                                                                                            ->getPattern() instanceof EnumClassPattern;
                $fetchObjectString = $isEnumeration ? "new {$className}(\$this->{$name}Id)" : "{$className}::dao()->getById(\$this->{$name}Id)";
                $method            = "\n{$classHint}\npublic function {$methodName}()\n{\n\tif (!\$this->{$name} && \$this->{$name}Id) {\n\t\t\$this->{$name} = {$fetchObjectString};\n\t}\n\t\n\treturn \$this->{$name};\n}\n\npublic function {$methodName}Id()\n{\n\treturn \$this->{$name}\n\t\t? \$this->{$name}->getId()\n\t\t: \$this->{$name}Id;\n}\n";
            } elseif ($property->getRelationId() == MetaRelation::ONE_TO_MANY || $property->getRelationId() == MetaRelation::MANY_TO_MANY) {
                $name          = $property->getName();
                $methodName    = ucfirst($name);
                $remoteName    = ucfirst($property->getName());
                $containerName = $class->getName().$remoteName.'DAO';
                $method        = "\n/**\n * @return {$containerName}\n**/\npublic function get{$methodName}(\$lazy = false)\n{\n\tif (!\$this->{$name} || (\$this->{$name}->isLazy() != \$lazy)) {\n\t\t\$this->{$name} = new {$containerName}(\$this, \$lazy);\n\t}\n\t\n\treturn \$this->{$name};\n}\n\n/**\n * @return {$class->getName()}\n**/\npublic function fill{$methodName}(\$collection, \$lazy = false)\n{\n\t\$this->{$name} = new {$containerName}(\$this, \$lazy);\n\t\n\tif (!\$this->id) {\n\t\tthrow new WrongStateException(\n\t\t\t'i do not know which object i belong to'\n\t\t);\n\t}\n\t\n\t\$this->{$name}->mergeList(\$collection);\n\t\n\treturn \$this;\n}\n";
            } else {
                $method = "\n{$classHint}\npublic function {$methodName}()\n{\n\treturn \$this->{$name};\n}\n";
            }
        }
        return $method;
    }

    public function toSetter(MetaClass $class, MetaClassProperty $property, MetaClassProperty $holder = null)
    {
        if ($property->getRelationId() == MetaRelation::ONE_TO_MANY || $property->getRelationId() == MetaRelation::MANY_TO_MANY) {
            // we don't need setter in such cases
            return null;
        }
        $name       = $property->getName();
        $methodName = 'set'.ucfirst($name);
        if ($holder) {
            return "\n/**\n * @return {$holder->getClass()->getName()}\n**/\npublic function {$methodName}({$property->getType()->getClassName()} \${$name})\n{\n\t\$this->{$holder->getName()}->{$methodName}(\${$name});\n\t\n\treturn \$this;\n}\n";
        } else {
            $defaultValue = $property->isOptional() ? ' = null' : '';
            if ($property->getFetchStrategyId() == FetchStrategy::LAZY) {
                $method = "\n/**\n * @return {$property->getClass()->getName()}\n**/\npublic function {$methodName}({$this->className} \${$name}{$defaultValue})\n{\n\t\$this->{$name} = \${$name};\n\t\$this->{$name}Id = \${$name} ? \${$name}->getId() : null;\n\n\treturn \$this;\n}\n\n/**\n * @return {$property->getClass()->getName()}\n**/\npublic function {$methodName}Id(\$id{$defaultValue})\n{\n\t\$this->{$name} = null;\n\t\$this->{$name}Id = \$id;\n\n\treturn \$this;\n}\n";
            } else {
                $method = "\n/**\n * @return {$property->getClass()->getName()}\n**/\npublic function {$methodName}({$this->className} \${$name}{$defaultValue})\n{\n\t\$this->{$name} = \${$name};\n\n\treturn \$this;\n}\n";
            }
        }
        return $method;
    }

    public function toDropper(MetaClass $class, MetaClassProperty $property, MetaClassProperty $holder = null)
    {
        if ($property->getRelationId() == MetaRelation::ONE_TO_MANY || $property->getRelationId() == MetaRelation::MANY_TO_MANY) {
            // we don't need dropper in such cases
            return null;
        }
        $name       = $property->getName();
        $methodName = 'drop'.ucfirst($name);
        if ($holder) {
            $method = "\n/**\n * @return {$holder->getClass()->getName()}\n**/\npublic function {$methodName}()\n{\n\t\$this->{$holder->getName()}->{$methodName}();\n\n\treturn \$this;\n}\n";
        } else {
            if ($property->getFetchStrategyId() == FetchStrategy::LAZY) {
                $method = "\n/**\n * @return {$class->getName()}\n**/\npublic function {$methodName}()\n{\n\t\$this->{$name} = null;\n\t\$this->{$name}Id = null;\n\n\treturn \$this;\n}\n";
            } else {
                $method = "\n/**\n * @return {$class->getName()}\n**/\npublic function {$methodName}()\n{\n\t\$this->{$name} = null;\n\n\treturn \$this;\n}\n";
            }
        }
        return $method;
    }

    public function toColumnType()
    {
        return $this->getClass()->getIdentifier()->getType()->toColumnType();
    }

    public function getHint()
    {
        return "/**\n * @return {$this->getClassName()}\n**/";
    }
}