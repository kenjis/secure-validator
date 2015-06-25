<?php

namespace Kenjis\Validation;

class ComplexTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->validator = new Validator();
        $this->validator
            ->add('email', 'email | required | maxlength(max=64)')// does the order matter?
            ->add('email_confirm', 'required | email | match(item=email) | maxlength(max=64)')
            ->add('password', 'required | maxlength(max=64)')
            ->add('password_confirm', 'required | match(item=password) | maxlength(max=64)')
            ->add('feedback', 'requiredwith(item=agree_to_provide_feedback) | maxlength(max=64)')
            ->add('birthday', 'requiredwhen', array('item' => 'email_confirm', 'rule' => 'Email'))
            ->add('birthday', 'maxlength(max=64)')
            // the lines below don't match the example but that's ok,
            // the individual rules have tests
            ->add('lines[*][price]', 'requiredwith(item=lines[*][quantity]) | maxlength(max=64)')
            ->remove('lines[*][price]', 'isString')
            ->add('lines[*][quantity]', 'required | maxlength(max=64)')
            ->remove('lines[*][quantity]', 'isString');
    }

    function testWithCorrectData()
    {
        $data = array(
            'email'                     => 'me@domain.com',
            'email_confirm'             => 'me@domain.com',
            'password'                  => '1234',
            'password_confirm'          => '1234',
            'agree_to_provide_feedback' => true,
            'feedback'                  => 'This is great!',
            'birthday'                  => '1980-01-01',
            'lines'                     => array(
                array('quantity' => 10, 'price' => 20)
            )
        );
        $this->assertTrue($this->validator->validate($data));

        $expected = [
            'email'                     => 'me@domain.com',
            'email_confirm'             => 'me@domain.com',
            'password'                  => '1234',
            'password_confirm'          => '1234',
            'feedback'                  => 'This is great!',
            'birthday'                  => '1980-01-01',
            'lines'                     => [
                ['quantity' => 10, 'price' => 20]
            ]
        ];
        $this->assertEquals($expected, $this->validator->getValidated());

        $this->assertEquals(
            'me@domain.com', $this->validator->getValidated('email')
        );
    }

    function testWithInvalidData()
    {
        $data = array(
            'email_confirm'             => 'me@domain.com',
            'password'                  => '1234',
            'password_confirm'          => '123456',
            'agree_to_provide_feedback' => true,
            'lines'                     => array(
                array('quantity' => 10, 'price' => null)
            )
        );
        $this->validator->validate($data);
        $messages = $this->validator->getMessages();

        $this->assertEquals(
            'This field is required', (string) $messages['email'][0]
        );
        $this->assertEquals(
            'This input does not match password',
            (string) $messages['password_confirm'][0]
        );
        $this->assertEquals(
            'This field is required', (string) $messages['feedback'][0]
        );
        $this->assertEquals(
            'This field is required', (string) $messages['lines[0][price]'][0]
        );
    }
}
