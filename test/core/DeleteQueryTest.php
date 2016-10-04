<?php

namespace onphp\test\core;

use onphp\core\DB\ImaginaryDialect;
use onphp\core\Exceptions\WrongArgumentException;
use onphp\core\Logic\Expression;
use onphp\core\OSQL\OSQL;
use onphp\test\misc\TestCase;

/* $Id$ */

final class DeleteQueryTest extends TestCase
{
    public function testQuery()
    {
        $query   = OSQL::delete()->from('pity_table');
        $dialect = ImaginaryDialect::me();
        try {
            $query->toDialectString($dialect);
            $this->fail();
        } catch (WrongArgumentException $e) {
        }
        $query->where(Expression::eq(1, 2));
        $this->assertEquals($query->toDialectString($dialect), 'DELETE FROM pity_table WHERE (1 = 2)');
        $query->andWhere(Expression::notEq('a', 'b'));
        $this->assertEquals($query->toDialectString($dialect), 'DELETE FROM pity_table WHERE (1 = 2) AND (a != b)');
    }
}