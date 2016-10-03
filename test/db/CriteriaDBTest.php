<?php

namespace onPHP\test\db;

use onPHP\core\Cache\Cache;
use onPHP\core\DB\DBPool;
use onPHP\main\Criteria\Criteria;
use onPHP\test\meta\Business\TestCity;
use onPHP\test\misc\DBTestPool;
use onPHP\test\misc\TestCaseDAO;

class CriteriaDBTest extends TestCaseDAO
{
    public function testCriteria()
    {
        foreach (DBTestPool::me()->getPool() as $db) {
            /* @var $db DB */
            DBPool::me()->setDefault($db);
            $this->getDBCreator()->fillDB();
            $queryResult = Criteria::create(TestCity::dao())->getResult();
            $this->assertEquals(2, $queryResult->getCount());
            Cache::me()->clean();
        }
    }
}