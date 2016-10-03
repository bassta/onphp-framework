<?php

namespace onphp\core\DB\Transaction;

use onPHP\core\Base\Enumeration;

/***************************************************************************
 *   Copyright (C) 2005-2007 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

/**
 * Transaction isolation levels.
 *
 * @see http://www.postgresql.org/docs/current/interactive/sql-start-transaction.html
 *
 * @ingroup Transaction
 **/
final class IsolationLevel extends Enumeration
{
    const READ_COMMITTED = 1;
    const READ_UNCOMMITTED = 2;
    const REPEATABLE_READ = 3;
    const SERIALIZABLE = 4;
    protected $names = array(self::READ_COMMITTED => 'read commited', self::READ_UNCOMMITTED => 'read uncommitted', self::REPEATABLE_READ => 'repeatable read', self::SERIALIZABLE => 'serializable');
}