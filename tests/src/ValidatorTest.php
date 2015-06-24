<?php

namespace Kenjis\Validation;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->obj = new Validator();
    }

    public function test_filter_trim()
    {
        $this->obj->add('field', 'required');
        $this->obj->add('field', 'maxlength', ['max' => 60]);
        $this->obj->filter('field', 'StringTrim');
        $input = [
            'field' => ' abc  ',
        ];
        $this->assertTrue($this->obj->validate($input));
        $this->assertEquals(
            'abc',
            $this->obj->getValidated('field')
        );
    }

    public function test_getInputValue_pass()
    {
        $this->obj->add('field', 'required');
        $this->obj->add('field', 'maxlength', ['max' => 60]);
        $this->obj->filter('field', 'StringTrim');
        $input = [
            'field' => ' abc  ',
        ];
        $this->assertTrue($this->obj->validate($input));
        $this->assertEquals(
            'abc',
            $this->obj->getInputValue('field')
        );
    }

    public function test_getInputValue_fail()
    {
        $this->obj->add('field', 'required');
        $this->obj->filter('field', 'StringTrim');
        $input = [
            'field' => ' abc  ',
        ];
        $this->assertFalse($this->obj->validate($input));
        $this->assertEquals(
            'abc',
            $this->obj->getInputValue('field')
        );
    }

    public function test_getInputValue_pass_and_not_exist()
    {
        $this->obj->add('field', 'required');
        $this->obj->add('field', 'maxlength', ['max' => 60]);
        $this->obj->filter('field', 'StringTrim');
        $input = [
            'field' => ' abc  ',
        ];
        $this->assertTrue($this->obj->validate($input));
        $this->assertNull(
            $this->obj->getInputValue('no_field')
        );
    }
}
