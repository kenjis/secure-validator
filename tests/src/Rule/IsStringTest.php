<?php

namespace Kenjis\Validation\Rule;

use Kenjis\Validation\Rule\IsString as Rule;

class IsStringTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->rule = new Rule();
    }

    public function testValidation()
    {
        $this->assertTrue($this->rule->validate('abc'));
        $this->assertTrue($this->rule->validate(''));
        $this->assertFalse($this->rule->validate(['abc']));
    }
}
