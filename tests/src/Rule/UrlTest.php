<?php

namespace Kenjis\Validation\Rule;

use Kenjis\Validation\Rule\Url as Rule;

class UrlTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->rule = new Rule();
    }

    /**
    * @dataProvider provider_valid_url
    */
    public function test_validate_pass($data)
    {
        $this->assertTrue($this->rule->validate($data));
    }

    public function provider_valid_url()
    {
        return array(
            array('http://www.example.jp/'),
            array('https://www.example.jp/'),
            array('http://www.example.jp/foo/'),
            array('https://www.example.jp/foo/'),
        );
    }

        /**
    * @dataProvider provider_invalid_url
    */
    public function test_validate_fail($data)
    {
        $this->assertFalse($this->rule->validate($data));
    }

    public function provider_invalid_url()
    {
        return array(
            array(''),
            array('www.example.jp'),
            array('ftp://ftp.example.jp/foo/'),
        );
    }
}
