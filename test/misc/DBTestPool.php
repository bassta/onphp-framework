<?php

namespace onphp\test\misc;

use onPHP\core\Base\Assert;
use onPHP\core\Base\Instantiatable;
use onPHP\core\Base\Singleton;
use onPHP\core\DB\DB;
use onPHP\core\DB\MySQLim;

final class DBTestPool extends Singleton implements Instantiatable
{
    private $pool = array();

    /**
     * @return DBTestPool
     */
    public static function me()
    {
        return Singleton::getInstance(__CLASS__);
    }

    protected function __construct(array $dbs = array())
    {
        Assert::isArray($dbs);
        foreach ($dbs as $connector => $credentials) {
            $this->pool[$connector] = DB::spawn($connector, $credentials['user'], $credentials['pass'], $credentials['host'], $credentials['base']);
            if ($this->pool[$connector] instanceof MySQLim) {
                $this->pool[$connector]->setDefaultEngine('INNODB');
            }
        }
    }

    public function disconnect()
    {
        foreach ($this->pool as $connector) {
            $connector->disconnect();
        }
    }

    public function connect($persistent = false)
    {
        foreach ($this->pool as $connector) {
            $connector->setPersistent($persistent)->connect();
        }
    }

    public function getPool()
    {
        return $this->pool;
    }
}