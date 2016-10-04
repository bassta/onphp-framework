<?php

namespace onphp\test\core;

use Exception;
use onphp\core\DB\DB;
use onphp\core\DB\DBPool;
use onphp\test\misc\TestCase;

final class DbConnectionTest extends TestCase
{
    public function setUp()
    {
        DBPool::me()->addLink('badLink', DB::spawn('PinbedPgSQL', 'postgres', '', 'localhost', 'wrongDatabase'));
    }

    public function testPostgresql()
    {
        try {
            $link = DBPool::me()->getLink('badLink');
            $this->fail('Unreachable code');
        } catch (Exception $e) {
            $this->assertInstanceOf('DatabaseException', $e);
        }
    }

    public function tearDown()
    {
        DBPool::me()->dropLink('badLink');
    }
}