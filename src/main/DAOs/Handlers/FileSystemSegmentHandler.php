<?php

namespace onphp\main\DAOs\Handlers;

use onphp\core\Base\Assert;
use onphp\core\Exceptions\BaseException;
use onphp\main\Utils\FileUtils;

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
 * @ingroup DAOs
 **/
final class FileSystemSegmentHandler implements SegmentHandler
{
    private $path = null;

    public function __construct($segmentId)
    {
        $path = ONPHP_TEMP_PATH.'fsdw'.DIRECTORY_SEPARATOR.$segmentId.DIRECTORY_SEPARATOR;
        try {
            mkdir($path, 448, true);
        } catch (BaseException $e) {
        }
        $this->path = $path;
    }

    public function touch($key)
    {
        try {
            return touch($this->path.$key);
        } catch (BaseException $e) {
            return false;
        }
        Assert::isUnreachable();
    }

    public function unlink($key)
    {
        try {
            return unlink($this->path.$key);
        } catch (BaseException $e) {
            return false;
        }
        Assert::isUnreachable();
    }

    public function ping($key)
    {
        return is_readable($this->path.$key);
    }

    public function drop()
    {
        // removed, but not created yet
        if (!is_writable($this->path)) {
            return true;
        }
        $toRemove = realpath($this->path).'.'.microtime(true).getmypid().'.'.'.removing';
        try {
            rename($this->path, $toRemove);
        } catch (BaseException $e) {
            // already removed during race
            return true;
        }
        FileUtils::removeDirectory($toRemove, true);
        return true;
    }
}