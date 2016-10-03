<?php

namespace onphp\test\main;

use onPHP\core\Exceptions\WrongArgumentException;
use onPHP\core\Logic\Expression;
use onPHP\core\OSQL\OSQL;
use onPHP\test\misc\TestCaseDB;

final class OsqlDeleteTest extends TestCaseDB
{
    public function testQuery()
    {
        $query   = OSQL::delete()->from('pity_table');
        $dialect = $this->getDbByType('PgSQL')->getDialect();
        try {
            $query->toDialectString($dialect);
            $this->fail();
        } catch (WrongArgumentException $e) {
        }
        $query->where(Expression::eq('count', 2));
        $this->assertEquals($query->toDialectString($dialect), 'DELETE FROM "pity_table" WHERE ("count" = \'2\')');
        $query->andWhere(Expression::notEq('a', '2'));
        $this->assertEquals($query->toDialectString($dialect), 'DELETE FROM "pity_table" WHERE ("count" = \'2\') AND ("a" != \'2\')');
    }
}