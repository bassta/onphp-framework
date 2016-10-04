<?php

namespace onphp\test\db;

use onphp\core\Cache\Cache;
use onphp\core\DB\DBPool;
use onphp\main\Criteria\Criteria;
use onphp\test\meta\Business\TestCity;
use onphp\test\misc\DBTestPool;
use onphp\test\misc\TestCaseDAO;

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