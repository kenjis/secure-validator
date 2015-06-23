<?php

namespace Kenjis\Validation\Rule;

use Kenjis\Validation\Rule\NoTabAndNewLine as Rule;

class NoTabAndNewLineTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->rule = new Rule();
    }

    public function test_validate_pass()
    {
        $this->assertTrue($this->rule->validate('String without tab and newline.'));
    }

    /**
    * @dataProvider provider_string_with_tab_and_newline
    */
    public function test_validate_fail($data)
    {
        $this->assertFalse($this->rule->validate($data));
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
