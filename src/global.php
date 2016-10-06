<?php
    namespace onphp;
    
    use onphp\core\Exceptions\BaseException;
    
    /***************************************************************************
 *   Copyright (C) 2004-2009 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

    // sample system-wide configuration file
    
    function error2Exception($code, $string, $file, $line, $context)
    {
        throw new BaseException($string, $code);
    }
    
    // file extensions
    define('EXT_CLASS', '.php');
    define('EXT_TPL', '.tpl.html');
    
    // overridable constant, don't forget for trailing slash
    // also you may consider using /dev/shm/ for cache purposes
    if (!defined('ONPHP_TEMP_PATH')) {
        $tempSuffix = 'onPHP';
        if (isset($_SERVER['USER'])) {
            $tempSuffix .= '-'.$_SERVER['USER'];
        }
        define(
            'ONPHP_TEMP_PATH',
            sys_get_temp_dir().DIRECTORY_SEPARATOR.$tempSuffix.DIRECTORY_SEPARATOR
        );
    }
    
    // system settings
    error_reporting(E_ALL | E_STRICT);
    //set_error_handler('error2Exception', E_ALL | E_STRICT);
    ignore_user_abort(true);
    define('ONPHP_VERSION', '1.1.master');
    
    if (!defined('ONPHP_IPC_PERMS'))
        define('ONPHP_IPC_PERMS', 0660);
    
    
    // paths
    define('ONPHP_ROOT_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
    define('ONPHP_CORE_PATH', ONPHP_ROOT_PATH.'core'.DIRECTORY_SEPARATOR);
    define('ONPHP_MAIN_PATH', ONPHP_ROOT_PATH.'main'.DIRECTORY_SEPARATOR);
    define('ONPHP_META_PATH', ONPHP_ROOT_PATH.'meta'.DIRECTORY_SEPARATOR);
    define('ONPHP_UI_PATH', ONPHP_ROOT_PATH.'UI'.DIRECTORY_SEPARATOR);
    
    if (!defined('ONPHP_META_PATH'))
        define(
            'ONPHP_META_PATH',
            ONPHP_ROOT_PATH.'meta'.DIRECTORY_SEPARATOR
        );
    /**
     * @deprecated
     */
    if (!defined('ONPHP_CURL_CLIENT_OLD_TO_STRING'))
        define('ONPHP_CURL_CLIENT_OLD_TO_STRING', false);
    
    //NOTE: disable by default
    //see http://pgfoundry.org/docman/view.php/1000079/117/README.txt
    //define('POSTGRES_IP4_ENABLED', true);