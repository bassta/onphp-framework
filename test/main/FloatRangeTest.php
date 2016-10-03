<?php

namespace onphp\test\main;

use onPHP\main\Base\FloatRange;
use onPHP\test\misc\TestCase;

final class FloatRangeTest extends TestCase
{
    /**
     * @dataProvider rangeDataProvider
     **/
    public function testCreation($min, $max, $throwsException)
    {
        if ($throwsException) {
            $this->setExpectedException('WrongArgumentException');
        }
        $range = FloatRange::create($min, $max);
    }

    public static function rangeDataProvider()
    {
        return array(array(1, 1, false), array(1, 2.222222222222222E+26, false), array(0.1, 1, false), array(0, 1, false), array(2, 1, false));
    }
}