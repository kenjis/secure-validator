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

    /**
     * @expectedException Kenjis\Validation\FatalValidationError
     */
    public function test_fatal_rule()
    {
        $this->obj->add('field', 'required');
        $this->obj->add('field', 'maxlength', ['max' => 3, 'fatal' => true]);
        $input = [
            'field' => '12345',
        ];
        $this->obj->validate($input);
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

    public function test_getValidated()
    {
        $this->obj->add('string', 'maxlength(max=60)');
        $this->obj->add('array', 'required');
        $this->obj->remove('array', 'isString');
        $this->obj->remove('array', 'MaxLength');
        $this->obj->add('array2', 'required');
        $this->obj->remove('array2', 'isString');
        $this->obj->remove('array2', 'MaxLength');
        $this->obj->add('array3', 'required');
        $this->obj->remove('array3', 'isString');
        $this->obj->remove('array3', 'MaxLength');
        $this->obj->add('array4', 'required');
        $this->obj->remove('array4', 'isString');
        $this->obj->remove('array4', 'MaxLength');

        $input = [
            'string' => 'abc',
            'array' => ['1', '2'],
            'array2' => ['key1' => 'val1', 'key2' => 'val2'],
            'array3' => [['abc'], ['xyz']],
            'array4' => ['a' => ['b' => ['c' => 'abc', 'd' => 'xyz']]],
        ];
        $this->assertTrue($this->obj->validate($input));

        $this->assertEquals(
            'abc',
            $this->obj->getValidated('string')
        );
        $this->assertEquals(
            ['1', '2'],
            $this->obj->getValidated('array')
        );
        $this->assertEquals(
            ['key1' => 'val1', 'key2' => 'val2'],
            $this->obj->getValidated('array2')
        );
        $this->assertEquals(
            [['abc'], ['xyz']],
            $this->obj->getValidated('array3')
        );
        $this->assertEquals(
            ['a' => ['b' => ['c' => 'abc', 'd' => 'xyz']]],
            $this->obj->getValidated('array4')
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

    public function test_add_callable()
    {
        $this->obj->add('field', 'Kenjis\Validation\FakeValidationRule::isTrue', null, 'Field must be string');
        $input = [
            'field' => 'a',
        ];
        $this->assertTrue($this->obj->validate($input));
    }

    public function test_validate_twice()
    {
        $this->obj->add('field', 'required');
        $this->obj->add('field', 'maxlength', ['max' => 60]);
        $input = [
            'field' => 'abc',
        ];
        $this->assertTrue($this->obj->validate($input));
        $this->assertTrue($this->obj->validate());
    }
}


class FakeValidationRule
{
    public static function isTrue($value, $field, $dataWrapper)
    {
        return true;
    }
}
