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

    public function test_add_array()
    {
        $this->obj->add([
            'field' => 'required | maxlength(max=60)'
        ]);
        $input = [
            'field' => 'abc',
        ];
        $this->assertTrue($this->obj->validate($input));
    }

    public function test_add_label()
    {
        $this->obj->add([
            'field:Label' => 'url | maxlength(max=60)'
        ]);
        $input = [
            'field' => 'abc',
        ];
        $this->assertFalse($this->obj->validate($input));
        $this->assertEquals(
            'Label is not a valid URL',
            (string) $this->obj->getMessages('field')[0]
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage If $selector is the only argument it must be an array
     */
    public function test_add_invalid_arg()
    {
        $this->obj->add('field');
        $input = [
            'field' => 'abc',
        ];
        $this->assertTrue($this->obj->validate($input));
    }
}
