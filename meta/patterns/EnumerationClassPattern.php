<?php

namespace onphp\meta\patterns;

use onPHP\meta\builders\EnumerationClassBuilder;
use onPHP\meta\classes\Format;
use onPHP\meta\classes\MetaClass;
use onPHP\meta\classes\MetaConfiguration;

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
 * @ingroup Patterns
 **/
class EnumerationClassPattern extends BasePattern
{
    public function daoExists()
    {
        return false;
    }

    public function tableExists()
    {
        return false;
    }

    /**
     * @return EnumerationClassPattern
     **/
    public function build(MetaClass $class)
    {
        $userFile = ONPHP_META_BUSINESS_DIR.$class->getName().EXT_CLASS;
        if (MetaConfiguration::me()->isForcedGeneration() || !file_exists($userFile)) {
            $this->dumpFile($userFile, Format::indentize(EnumerationClassBuilder::build($class)));
        }
        return $this;
    }
}