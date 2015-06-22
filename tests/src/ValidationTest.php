<?php

namespace Kenjis\Validation;

class ValidationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->obj = new Validator();
    }

    public function test_default_max_lenght()
    {
        $this->obj->add('field', 'required');
        $input = [
            'field' => 'abc',
        ];
        $this->assertFalse($this->obj->validate($input));
        $this->assertEquals('This input should have less than 1 characters', (string) $this->obj->getMessages('field')[0]);
    }

    public function test_overwrite_max_lenght()
    {
        $this->obj->add('field', 'required');
        $this->obj->add('field', 'maxlength', ['max' => 60]);
        $input = [
            'field' => 'abc',
        ];
        $this->assertTrue($this->obj->validate($input));
        $this->assertEquals([], $this->obj->getMessages('field'));
    }
}
