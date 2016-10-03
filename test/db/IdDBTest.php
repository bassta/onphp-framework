<?php

namespace onPHP\test\db;

use onPHP\core\DB\DBPool;
use onPHP\core\Exceptions\WrongArgumentException;
use onPHP\test\meta\Business\TestLazy;
use onPHP\test\meta\Business\TestStringIdentifier;
use onPHP\test\meta\Business\TestStringIdentifierRelated;
use onPHP\test\meta\Business\TestUser;
use onPHP\test\misc\DBTestPool;
use onPHP\test\misc\TestCaseDAO;

class IdDBTest extends TestCaseDAO
{
    public function testGetByEmptyId()
    {
        foreach (DBTestPool::me()->getPool() as $db) {
            DBPool::me()->setDefault($db);
            $this->getByEmptyIdTest(0);
            $this->getByEmptyIdTest(null);
            $this->getByEmptyIdTest('');
            $this->getByEmptyIdTest('0');
            $this->getByEmptyIdTest(false);
            $empty = TestLazy::create();
            $this->assertNull($empty->getCity());
            $this->assertNull($empty->getCityOptional());
            $this->assertNull($empty->getEnum());
            $this->assertNull($empty->getStaticEnum());
        }
    }

    public function testStringIdentifier()
    {
        $identifier = TestStringIdentifier::proto()->getPropertyByName('id');
        $this->assertEquals($identifier->getType(), 'scalarIdentifier');
        $identifier = TestStringIdentifierRelated::proto()->getPropertyByName('test');
        $this->assertEquals($identifier->getType(), 'scalarIdentifier');
    }

    private function getByEmptyIdTest($id)
    {
        try {
            TestUser::dao()->getById($id);
            $this->fail();
        } catch (WrongArgumentException $e) {
        }
    }
}