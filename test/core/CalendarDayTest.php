<?php

namespace onPHP\test\core;

use onPHP\main\Base\CalendarDay;
use onPHP\test\misc\TestCase;

final class CalendarDayTest extends TestCase
{
    public function testSleeping()
    {
        $calendarDay     = CalendarDay::create('1984-03-25')->setOutside(true)->setSelected(false);
        $serializedDay   = serialize($calendarDay);
        $unserializedDay = unserialize($serializedDay);
        $this->assertEquals($calendarDay->getDay(), $unserializedDay->getDay());
        $this->assertEquals($calendarDay->getMonth(), $unserializedDay->getMonth());
        $this->assertEquals($calendarDay->getYear(), $unserializedDay->getYear());
        $this->assertEquals($calendarDay->isOutside(), $unserializedDay->isOutside());
        $this->assertEquals($calendarDay->isSelected(), $unserializedDay->isSelected());
    }
}