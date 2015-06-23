<?php

namespace Kenjis\Validation\Rule;

use Kenjis\Validation\Rule\IsString as Rule;

class IsStringTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->rule = new Rule();
    }

    public function test_valiate_pass()
    {
        $this->assertTrue($this->rule->validate('abc'));
        $this->assertTrue($this->rule->validate(''));
    }

    public function test_validate_fail()
    {
        $this->assertFalse($this->rule->validate(['abc']));
    }
}
