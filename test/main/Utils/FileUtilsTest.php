<?php

namespace onPHP\test\main\Utils;

use onPHP\main\Utils\FileUtils;
use onPHP\test\misc\TestCase;

final class FileUtilsTest extends TestCase
{
    public function testUniqueNames()
    {
        $uniqueName = FileUtils::makeUniqueName('Chasey Lain.jpeg');
        $this->assertEquals(substr($uniqueName, strpos($uniqueName, '.')), '.jpeg');
        $uniqueName = FileUtils::makeUniqueName('Елена Беркова.jpeg');
        $this->assertEquals(substr($uniqueName, strpos($uniqueName, '.')), '.jpeg');
        $uniqueName = FileUtils::makeUniqueLatinName('Елена Беркова.gif');
        //animated gif ;)
        $this->assertEquals(substr($uniqueName, strpos($uniqueName, '.')), '.gif');
    }
}