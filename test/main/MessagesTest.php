<?php

namespace onphp\test\main;

use onPHP\main\Messages\TextFileQueue;
use onPHP\main\Messages\TextFileReceiver;
use onPHP\main\Messages\TextFileSender;
use onPHP\main\Messages\TextMessage;
use onPHP\test\misc\TestCase;

/* $Id$ */

final class MessagesTest extends TestCase
{
    public function testFileQueue()
    {
        $dir = ONPHP_TEMP_PATH.'tests/messages';
        $uri = $dir.'/fileQueueItems';
        if (!is_dir($dir)) {
            mkdir($dir, 448, true);
        }
        if (file_exists($uri)) {
            unlink($uri);
        }
        $queue    = TextFileQueue::create()->setFileName($uri);
        $sender   = TextFileSender::create()->setQueue($queue);
        $receiver = TextFileReceiver::create()->setQueue($queue);
        $sender->send(TextMessage::create()->setText('first ape'));
        $sender->send(TextMessage::create()->setText('second ape'));
        $message = $receiver->receive();
        $this->assertNotNull($message);
        $this->assertEquals('first ape', $message->getText());
        $message = $receiver->receive();
        $this->assertNotNull($message);
        $this->assertEquals('second ape', $message->getText());
        $sender->send(TextMessage::create()->setText('third ape'));
        $message = $receiver->receive();
        $this->assertNotNull($message);
        $this->assertEquals('third ape', $message->getText());
    }
}