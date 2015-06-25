<?php

namespace Kenjis\Validation;

use Sirius\Validation\RuleFactory;
use Sirius\Validation\ErrorMessage;

class ValueValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->prepareObjects();
        
        $this->obj = new ValueValidator($this->ruleFactory, $this->errorMessagePrototype);
    }

    protected function prepareObjects()
    {
        $this->ruleFactory = new RuleFactory();
        $this->errorMessagePrototype = new ErrorMessage();
        
        $rulesClasses = array(
            'IsString',
            'NoControl',
            'NoTabAndNewLine',
            'ValidUtf8',
            'Url'   // overwrite
        );
        foreach ($rulesClasses as $class) {
            $fullClassName = '\\' . __NAMESPACE__ . '\Rule\\' . $class;
            $name = strtolower(str_replace('\\', '', $class));
            $errorMessage = constant($fullClassName . '::MESSAGE');
            $labeledErrorMessage = constant($fullClassName . '::LABELED_MESSAGE');
            $this->ruleFactory->register($name, $fullClassName, $errorMessage, $labeledErrorMessage);
        }
    }

    public function test_remove_all()
    {
        $this->assertEquals(4, count($this->obj->getRules()));
        $this->obj->remove();
        $this->assertEquals(0, count($this->obj->getRules()));
    }

    public function test_label()
    {
        $label = 'Label';
        $this->obj = new ValueValidator($this->ruleFactory, $this->errorMessagePrototype, $label);

        $this->obj->validate('value');
        $this->assertEquals(
            'Label should have less than 1 characters',
            (string)$this->obj->getMessages()[0]
        );
    }
}
