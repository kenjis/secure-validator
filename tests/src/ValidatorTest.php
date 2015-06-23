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
}
