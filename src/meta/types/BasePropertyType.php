<?php

namespace onphp\meta\types;

use onphp\core\Base\Assert;
use onphp\core\Exceptions\UnsupportedMethodException;
use onphp\meta\classes\MetaClass;
use onphp\meta\classes\MetaClassProperty;

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
 * @ingroup Types
 **/
abstract class BasePropertyType
{
    public abstract function getDeclaration();

    public abstract function isMeasurable();

    public abstract function toColumnType();

    public abstract function getPrimitiveName();

    protected $default = null;

    public function isGeneric()
    {
        return true;
    }

    public function toMethods(MetaClass $class, MetaClassProperty $property, MetaClassProperty $holder = null)
    {
        return $this->toGetter($class, $property, $holder).$this->toSetter($class, $property, $holder);
    }

    public function hasDefault()
    {
        return $this->default !== null;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function setDefault($default)
    {
        throw new UnsupportedMethodException('only generic non-object types can have default values atm');
    }

    public function toGetter(MetaClass $class, MetaClassProperty $property, MetaClassProperty $holder = null)
    {
        if ($holder) {
            $name = $holder->getName().'->get'.ucfirst($property->getName()).'()';
        } else {
            $name = $property->getName();
        }
        $methodName = 'get'.ucfirst($property->getName());
        return "\npublic function {$methodName}()\n{\n\treturn \$this->{$name};\n}\n";
    }

    public function toSetter(MetaClass $class, MetaClassProperty $property, MetaClassProperty $holder = null)
    {
        $name       = $property->getName();
        $methodName = 'set'.ucfirst($name);
        if ($holder) {
            return "\n/**\n * @return {$holder->getClass()->getName()}\n**/\npublic function {$methodName}(\${$name})\n{\n\t\$this->{$holder->getName()}->{$methodName}(\${$name});\n\n\treturn \$this;\n}\n";
        } else {
            return "\n/**\n * @return {$class->getName()}\n**/\npublic function {$methodName}(\${$name})\n{\n\t\$this->{$name} = \${$name};\n\n\treturn \$this;\n}\n";
        }
        Assert::isUnreachable();
    }

    public function getHint()
    {
        return null;
    }
}