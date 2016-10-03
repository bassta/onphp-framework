<?php

namespace onphp\test\main\Utils;

use onPHP\main\Utils\TypesUtils;
use onPHP\test\misc\TestCase;

final class TypesUtilsTest extends TestCase
{
    /**
     * @dataProvider integers
     **/
    public function testSignedToUnsigned($signed, $unsigned)
    {
        $this->assertEquals(TypesUtils::signedToUnsigned($signed), $unsigned);
    }

    /**
     * @dataProvider integers
     **/
    public function testUnsignedToSigned($signed, $unsigned)
    {
        $this->assertEquals($signed, TypesUtils::unsignedToSigned($unsigned));
    }

    public static function integers()
    {
        return array(array('-926365496', '3368601800'), array('16843009', '16843009'));
    }
}