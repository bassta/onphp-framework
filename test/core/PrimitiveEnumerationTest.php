<?php

namespace onPHP\test\core;

use onPHP\core\Form\Form;
use onPHP\core\Form\Primitive;
use onPHP\core\OSQL\DataType;
use onPHP\test\misc\TestCase;

/* $Id$ */

final class PrimitiveEnumerationTest extends TestCase
{
    public function testIntegerValues()
    {
        $form = Form::create()->add(Primitive::enumeration('enum')->of('DataType'));
        $form->import(array('enum' => '4097'));
        $this->assertEquals($form->getValue('enum')->getId(), 4097);
        $this->assertSame($form->getValue('enum')->getId(), 4097);
    }

    public function testGetList()
    {
        $primitive = Primitive::enumeration('enum')->of('DataType');
        $enum      = DataType::create(DataType::getAnyId());
        $this->assertEquals($primitive->getList(), $enum->getObjectList());
        $primitive->setDefault($enum);
        $this->assertEquals($primitive->getList(), $enum->getObjectList());
        $primitive->import(array('enum' => DataType::getAnyId()));
        $this->assertEquals($primitive->getList(), $enum->getObjectList());
    }
}