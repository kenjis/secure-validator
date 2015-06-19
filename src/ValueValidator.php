<?php

namespace Kenjis\Validation;

use Sirius\Validation\RuleFactory;
use Sirius\Validation\ErrorMessage;
use Sirius\Validation\RuleCollection;

class ValueValidator extends \Sirius\Validation\ValueValidator
{
    function __construct(RuleFactory $ruleFactory = null, ErrorMessage $errorMessagePrototype = null, $label = null)
    {
        if (!$ruleFactory) {
            $ruleFactory = new RuleFactory();
        }
        $this->ruleFactory = $ruleFactory;
        if (!$errorMessagePrototype) {
            $errorMessagePrototype = new ErrorMessage();
        }
        $this->errorMessagePrototype = $errorMessagePrototype;
        if ($label) {
            $this->label = $label;
        }
        $this->rules = new RuleCollection;
        
        // add default rules [Added by kenjis]
        $this->add('Kenjis\Validation\Rule\IsString');
        $this->add('Kenjis\Validation\Rule\ValidUtf8');
        $this->add('Kenjis\Validation\Rule\NoControl');
        $this->add('maxlength', ['max' => 1]);
    }
}
