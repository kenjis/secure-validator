<?php

namespace Kenjis\Validation\Rule;

use Kenjis\Validation\Rule\ValidUtf8 as Rule;

class ValidUtf8Test extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->rule = new Rule();
    }

    public function test_valiate_pass()
    {
        $input = 'UTF-8の文字列です。';
        $this->assertTrue($this->rule->validate($input));
    }

    public function test_validate_fail()
    {
        $input = mb_convert_encoding('SJISの文字列です。', 'SJIS');
        $this->assertFalse($this->rule->validate($input));
    }

    public function test_valiate_pass_array()
    {
        $input = [
            'UTF-8の文字列です。',
            'UTF-8の文字列です。',
        ];
        $this->assertTrue($this->rule->validate($input));
    }

    public function test_validate_fail_array()
    {
        $input = [
            'UTF-8の文字列です。',
            mb_convert_encoding('SJISの文字列です。', 'SJIS'),
        ];
        $this->assertFalse($this->rule->validate($input));
    }
}
