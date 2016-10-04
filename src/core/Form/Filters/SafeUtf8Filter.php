<?php

namespace onphp\core\Form\Filters;

use onphp\core\Base\Singleton;

/***************************************************************************
 *   Copyright (C) 2007 by Ivan Y. Khvostishkov                            *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

/**
 * @ingroup Filters
 **/
final class SafeUtf8Filter extends BaseFilter
{
    /**
     * @return SafeUtf8Filter
     **/
    public static function me()
    {
        return Singleton::getInstance(__CLASS__);
    }

    public function apply($value)
    {
        $matches = null;
        // voodoo magic from w3 validator
        preg_match_all('/[\\x00-\\x7F]                         '.'| [\\xC2-\\xDF]        [\\x80-\\xBF]    '.'|  \\xE0[\\xA0-\\xBF]   [\\x80-\\xBF]    '.'| [\\xE1-\\xEC\\xEE\\xEF][\\x80-\\xBF]{2} '.'|  \\xED[\\x80-\\x9F]   [\\x80-\\xBF]    '.'|  \\xF0[\\x90-\\xBF]   [\\x80-\\xBF]{2} '.'| [\\xF1-\\xF3]        [\\x80-\\xBF]{3} '.'|  \\xF4[\\x80-\\x8F][\\x80-\\xBF]{2}    '.'/x', $value, $matches);
        if (!isset($matches[0])) {
            return null;
        } else {
            return implode(null, $matches[0]);
        }
    }
}