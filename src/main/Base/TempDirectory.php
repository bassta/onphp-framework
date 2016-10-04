<?php

namespace onphp\main\Base;

use onphp\core\Exceptions\BaseException;
use onphp\main\Utils\FileUtils;

/***************************************************************************
 *   Copyright (C) 2007 by Ivan Y. Khvostishkov                            *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/
final class TempDirectory
{
    private $path = null;

    public function __construct($directory = 'temp-garbage/', $prefix = 'TmpDir')
    {
        $this->path = FileUtils::makeTempDirectory($directory, $prefix);
    }

    public function __destruct()
    {
        try {
            FileUtils::removeDirectory($this->path, true);
        } catch (BaseException $e) {
        }
    }

    public function getPath()
    {
        return $this->path;
    }
}