<?php

namespace Kenjis\Validation;

use Sirius\Validation\Rule\AbstractRule;

class FatalValidationErrorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->obj = new FatalValidationError();
    }

    public function test_getRule()
    {
        $rule = new Rule\IsString();
        $this->obj->setRule($rule, 'abc', null);
        $this->assertTrue($this->obj->getRule() instanceof AbstractRule);
    }

    public function test_getValue()
    {
        $rule = new Rule\IsString();
        $this->obj->setRule($rule, 'abc', null);
        $this->assertEquals('abc', $this->obj->getValue());
    }

    public function test_getvalueIdentifier()
    {
        $rule = new Rule\IsString();
        $this->obj->setRule($rule, 'abc', 'field');
        $this->assertEquals('field', $this->obj->getvalueIdentifier());
    }
}
