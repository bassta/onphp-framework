<?php

namespace onphp\test\core;

use onphp\core\Base\Singleton;
use onphp\core\Exceptions\BaseException;
use onphp\core\Exceptions\MissingElementException;
use onphp\core\Exceptions\WrongArgumentException;
use onphp\test\misc\TestCase;
use ReflectionClass;

/* $Id$ */

final class SingletonTestInstance extends Singleton
{
}

final class SingletonSingleArgumentTestInstance extends Singleton
{
    protected function __construct($arg1)
    {
    }
}

final class SingletonMultiArgumentTestInstance extends Singleton
{
    protected function __construct($arg1, $arg2, $arg3 = null)
    {
    }
}

final class SingletonTest extends TestCase
{
    const CLASS_NAME = 'onphp\test\core\SingletonTestInstance';
    const SINGLE_CLASS_NAME = 'onphp\test\core\SingletonSingleArgumentTestInstance';
    const MULTI_CLASS_NAME = 'onphp\test\core\SingletonMultiArgumentTestInstance';

    public function testFactoryLikeCall()
    {
        $this->assertSameInstances(self::CLASS_NAME, Singleton::getInstance(self::CLASS_NAME), Singleton::getInstance(self::CLASS_NAME));
    }

    public function testNonSingletonChilds()
    {
        try {
            Singleton::getInstance('onphp\core\Base\Ternary');
            $this->fail();
        } catch (WrongArgumentException $e) {
        }
    }

    public function testCreationProhibition()
    {
        $child = new ReflectionClass(self::CLASS_NAME);
        $this->assertFalse($child->getMethod('__construct')->isPublic());
        $this->assertTrue($child->getMethod('__construct')->isProtected());
        $this->assertTrue($child->getMethod('__sleep')->isFinal());
        $this->assertTrue($child->getMethod('__sleep')->isPrivate());
        $this->assertTrue($child->getMethod('__clone')->isFinal());
        $this->assertTrue($child->getMethod('__clone')->isPrivate());
    }

    public function testArguments()
    {
        // cleaning up
        try {
            Singleton::dropInstance(self::SINGLE_CLASS_NAME);
        } catch (MissingElementException $e) {
        }
        try {
            Singleton::getInstance(self::SINGLE_CLASS_NAME);
            $this->fail();
        } catch (BaseException $e) {
        }
        $this->assertSameInstances(
            self::SINGLE_CLASS_NAME,
            Singleton::getInstance(self::SINGLE_CLASS_NAME, 'val1'),
            Singleton::getInstance(self::SINGLE_CLASS_NAME, 'val2')
        );
        $this->assertSameInstances(
            self::MULTI_CLASS_NAME,
            Singleton::getInstance(self::MULTI_CLASS_NAME, 'val1', 'val2', 'val3'),
            Singleton::getInstance(self::MULTI_CLASS_NAME, 'val1', 'val2')
        );
    }

    private function assertSameInstances($className, Singleton $instance1, Singleton $instance2)
    {
        $this->assertTrue($instance1 === $instance2);
        $all = Singleton::getAllInstances();
        $this->assertTrue($instance1 === $all[$className]);
    }
}