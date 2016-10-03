<?php

namespace onphp\test\db;

use onPHP\core\DB\DB;
use onPHP\core\DB\PgSQL;
use onPHP\test\misc\DBTestPool;
use onPHP\test\misc\TestCaseDB;

/**
 * @group pgsf
 */
class PgSQLFullTextSearchDBTest extends TestCaseDB
{
    /**
     * @test
     */
    public function getPgSQL()
    {
        foreach (DBTestPool::me()->getPool() as $db) {
            if ($db instanceof PgSQL) {
                return $db;
            }
        }
        $this->markTestIncomplete('Required PgSQL for testing');
    }

    /**
     * @depends getPgSQL
     * @return PgSQL
     */
    public function testPrepairFullText(PgSQL $db)
    {
        $this->assertEquals('\'новый год\' & \'снегурочка\' & \'праздник\'', $db->getDialect()
                                                                                ->prepareFullText(array('Новый год', 'Снегурочка', 'ПрАзДнИк'), DB::FULL_TEXT_AND));
    }
}