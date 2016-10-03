<?php

namespace onphp\meta\classes;

use onPHP\core\Base\StaticFactory;

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
 * @ingroup MetaBase
 **/
final class Format extends StaticFactory
{
    // dumb and straigh beautifier
    public static function indentize($data)
    {
        $out    = null;
        $indent = 0;
        $chain  = 1;
        $first  = true;
        // for schema.php-like files
        foreach (explode('
', $data) as $string) {
            $string = preg_replace('~^[\\t]+~', null, rtrim($string)).'
';
            if ($string == '}
'
            ) {
                $indent -= $chain;
                $chain = 1;
            } elseif ($string == ')->
'
            ) {
                --$indent;
            } elseif ($string == ')
'
            ) {
                --$indent;
            } elseif ($string == ');
'
            ) {
                --$indent;
            } elseif ($string == '),
'
            ) {
                --$indent;
            } elseif ($string == '?>
'
            ) {
                $indent = 0;
            } elseif ($string[0] == '?') {
                ++$indent;
            }
            if ($string != '
'
            ) {
                if ($indent > 0) {
                    $out .= str_pad(null, $indent, '	', STR_PAD_LEFT).$string;
                } else {
                    $out .= $string;
                }
            }
            if (substr($string, -2, 2) == '{
'
            ) {
                ++$indent;
            } elseif (substr_count($string, '\'') == 2 && substr($string, -3, 3) == '=>
'
            ) {
                ++$indent;
                ++$chain;
            } elseif ($string[0] == '$' && (substr($string, -2, 2) == '=
' || substr($string, -3, 3) == '->
')
            ) {
                ++$indent;
                ++$chain;
            } elseif (substr($string, -2, 2) == '(
'
            ) {
                ++$indent;
            } elseif ($string == '
' && $indent == 0
            ) {
                ++$indent;
            } elseif ($string == 'return
'
            ) {
                ++$indent;
                ++$chain;
            } elseif ($string == '
' && $chain > 1
            ) {
                $indent -= $chain - 1;
                $chain = 1;
            } elseif ($string[0] == ':') {
                --$indent;
            } elseif ($string == '),
'
            ) {
                --$indent;
            }
            if ($string == '
'
            ) {
                if (!$first && $indent > 0) {
                    $out .= str_pad(null, $indent, '	', STR_PAD_LEFT).$string;
                } else {
                    $out .= $string;
                    $first = false;
                }
            }
        }
        return $out;
    }
}