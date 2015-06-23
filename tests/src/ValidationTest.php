<?php

namespace Kenjis\Validation;

class ValidationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->obj = new Validator();
    }

    public function test_default_MaxLenght()
    {
        $this->obj->add('field', 'required');
        $input = [
            'field' => 'abc',
        ];
        $this->assertFalse($this->obj->validate($input));
        $this->assertEquals(
            'This input should have less than 1 characters',
            (string) $this->obj->getMessages('field')[0]
        );
    }

    public function test_overwrite_MaxLenght()
    {
        $this->obj->add('field', 'required');
        $this->obj->add('field', 'maxlength', ['max' => 60]);
        $input = [
            'field' => 'abc',
        ];
        $this->assertTrue($this->obj->validate($input));
        $this->assertEquals([], $this->obj->getMessages('field'));
    }

    public function test_default_IsString()
    {
        $this->obj->add('field', 'required');
        $input = [
            'field' => ['abc', 'def'],
        ];
        $this->assertFalse($this->obj->validate($input));
        $this->assertEquals(
            'This input is not a valid string',
            (string) $this->obj->getMessages('field')[0]
        );
    }

    public function test_default_NoControl()
    {
        $this->obj->add('a', 'required');
        $this->obj->add('b', 'required');
        $this->obj->add('c', 'required');
        $input = [
            'a' => rawurldecode('%001234'), // null byte     -> fail
            'b' => rawurldecode('%0a1234'), // linefeed      -> pass
            'c' => rawurldecode('%181234'), // controll char -> fail
        ];
        $this->assertFalse($this->obj->validate($input));

//        $rules = $this->obj->getRules();
//        foreach ($rules as $selector => $valuevalidator) {
//            echo $selector, PHP_EOL;
//            $rulecollection = $valuevalidator->getRules();
//            foreach ($rulecollection as $key => $val) {
//                var_dump(get_class($val));
//            }
//        }

        $this->assertEquals(
            'This input has control character',
            (string) $this->obj->getMessages('a')[0]
        );
        $this->assertEquals(
            'This input should have less than 1 characters',
            (string) $this->obj->getMessages('b')[0]
        );
        $this->assertEquals(
            'This input has control character',
            (string) $this->obj->getMessages('c')[0]
        );
    }
}
