<?php

namespace onPHP\test\main;

use onPHP\core\Exceptions\MissingElementException;
use onPHP\main\Utils\ClassUtils;
use onPHP\test\misc\TestCase;

/* $Id$ */

final class EnumerationTest extends TestCase
{
    public function testAnyId()
    {
        foreach (get_declared_classes() as $className) {
            if (is_subclass_of($className, 'Enumeration')) {
                try {
                    $enum = new $className(call_user_func(array($className, 'getAnyId')));
                } catch (MissingElementException $e) {
                    $this->fail($className);
                }
            } elseif (is_subclass_of($className, 'Enum')) {
                try {
                    $enum = new $className(ClassUtils::callStaticMethod($className.'::getAnyId'));
                } catch (MissingElementException $e) {
                    $this->fail($className);
                }
            }
        }
    }
}