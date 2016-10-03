<?php

namespace onPHP\main\Net\Soap;

use onPHP\main\EntityProto\Builders\ObjectToDTOConverter;
use onPHP\main\EntityProto\PrototypedEntity;

/***************************************************************************
 *   Copyright (C) 2007 by Ivan Y. Khvostishkov                            *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
abstract class DTOMessage implements PrototypedEntity
{
    public final function makeDto()
    {
        return ObjectToDTOConverter::create($this->entityProto())->make($this);
    }
}