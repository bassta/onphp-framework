<?php

namespace onphp\test\main\Utils;

use onPHP\main\Utils\IO\Socket;
use onPHP\test\misc\TestCase;

final class SocketTest extends TestCase
{
    public function testShutdown()
    {
        Socket::create()->setHost('localhost')->setPort(80)->close();
        $timedOutSocket = Socket::create()->setHost('google.com')->setPort(80)->setTimeout(1)->connect();
        $timedOutSocket->write('GET / HTTP/1.1
Host: google.com

');
        $timedOutSocket->read(256);
        $timedOutSocket->close();
        $this->assertEquals(42, 42);
    }
}