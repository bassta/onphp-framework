<?php

namespace onPHP\meta\types;

use onPHP\core\Base\Assert;
use onPHP\core\Exceptions\WrongArgumentException;
use onPHP\meta\classes\MetaClass;
use onPHP\meta\classes\MetaClassProperty;

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
class BooleanType extends BasePropertyType
{
    public function getPrimitiveName()
    {
        return 'boolean';
    }

    /**
     * @throws WrongArgumentException
     * @return BooleanType
     **/
    public function setDefault($default)
    {
        static $boolean = array('true' => true, 'false' => false);
        if (!isset($boolean[$default])) {
            throw new WrongArgumentException("strange default value given - '{$default}'");
        }
        $this->default = $boolean[$default];
        return $this;
    }

    public function getDeclaration()
    {
        if ($this->hasDefault()) {
            return $this->default ? 'true' : 'false';
        }
        return 'null';
    }

    public function isMeasurable()
    {
        return false;
    }

    public function toColumnType()
    {
        return 'DataType::create(DataType::BOOLEAN)';
    }

    public function toGetter(MetaClass $class, MetaClassProperty $property, MetaClassProperty $holder = null)
    {
        $name       = $property->getName();
        $camelName  = ucfirst($name);
        $methodName = "is{$camelName}";
        $compatName = "get{$camelName}";
        if ($holder) {
            return "\npublic function {$compatName}()\n{\n\treturn \$this->{$holder->getName()}->{$compatName}();\n}\n\npublic function {$methodName}()\n{\n\treturn \$this->{$holder->getName()}->{$methodName}();\n}\n";
        } else {
            return "\npublic function {$compatName}()\n{\n\treturn \$this->{$name};\n}\n\npublic function {$methodName}()\n{\n\treturn \$this->{$name};\n}\n";
        }
        Assert::isUnreachable();
    }

    public function toSetter(MetaClass $class, MetaClassProperty $property, MetaClassProperty $holder = null)
    {
        $name       = $property->getName();
        $methodName = 'set'.ucfirst($name);
        if ($holder) {
            return "\n/**\n * @return {$holder->getClass()->getName()}\n**/\npublic function {$methodName}(\${$name})\n{\n\t\$this->{$holder->getName()}->{$methodName}(\${$name});\n\n\treturn \$this;\n}\n";
        } else {
            if ($property->isRequired()) {
                $method = "\n/**\n * @return {$class->getName()}\n**/\npublic function {$methodName}(\${$name} = false)\n{\n\t\$this->{$name} = (\${$name} === true);\n\n\treturn \$this;\n}\n";
            } else {
                $method = "\n/**\n * @return {$class->getName()}\n**/\npublic function {$methodName}(\${$name} = null)\n{\n\tAssert::isTernaryBase(\${$name});\n\t\n\t\$this->{$name} = \${$name};\n\n\treturn \$this;\n}\n";
            }
        }
        return $method;
    }
}