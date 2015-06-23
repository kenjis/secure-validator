<?php

namespace Kenjis\Validation\Rule;

use Kenjis\Validation\Rule\NoControl as Rule;

class NoControlTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->rule = new Rule();
    }

    /**
    * @dataProvider provider_string_with_control_chars
    */
    public function test_validate_fail($data)
    {
        $this->assertFalse($this->rule->validate($data));
    }

    public function provider_string_with_control_chars()
    {
        return array(
            array("This is string with null byte.\0"),
            array("This is string wiht null byte and newline.\0\n"),
        );
    }

    /**
    * @dataProvider provider_string_with_tab_and_newline
    */
    public function test_validate_pass($data)
    {
        $this->assertTrue($this->rule->validate($data));
    }

    public function provider_string_with_tab_and_newline()
    {
        return array(
            array("This is string with newline.\n"),
            array("This is string with\r newline."),
            array("This is \r\nstring with newline."),
            array("This is string with tab.\t"),
            array("This is\r string with \tnewline\ns and tab."),
        );
    }
}
