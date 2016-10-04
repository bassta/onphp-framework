<?php

namespace onphp\test\core;

use onphp\core\Form\Form;
use onphp\core\Form\Primitive;
use onphp\core\OSQL\DataType;
use onphp\test\misc\TestCase;

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