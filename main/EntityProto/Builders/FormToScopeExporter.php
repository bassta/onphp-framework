<?php

namespace onPHP\main\EntityProto\Builders;

use onPHP\main\EntityProto\Accessors\FormExporter;
use onPHP\main\EntityProto\Accessors\ScopeSetter;
use onPHP\main\EntityProto\EntityProto;

/***************************************************************************
 *   Copyright (C) 2008 by Ivan Y. Khvostishkov                            *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
final class FormToScopeExporter extends ObjectBuilder
{
    /**
     * @return FormToObjectConverter
     **/
    public static function create(EntityProto $proto)
    {
        return new self($proto);
    }

    protected function createEmpty()
    {
        return array();
    }

    /**
     * @return FormGetter
     **/
    protected function getGetter($object)
    {
        return new FormExporter($this->proto, $object);
    }

    /**
     * @return ObjectSetter
     **/
    protected function getSetter(&$object)
    {
        return new ScopeSetter($this->proto, $object);
    }
}