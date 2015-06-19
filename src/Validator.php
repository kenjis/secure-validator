<?php

namespace Kenjis\Validation;

use Sirius\Validation\RuleFactory;

class Validator extends \Sirius\Validation\Validator
{
    protected $validatedData = [];

    public function __construct(RuleFactory $ruleFactory = null, ErrorMessage $errorMessagePrototype = null)
    {
        parent::__construct($ruleFactory, $errorMessagePrototype);

        $rulesClasses = array(
            'IsString',
            'NoControl',
            'NoTabAndNewLine',
            'ValidUtf8',
        );
        foreach ($rulesClasses as $class) {
            $fullClassName = '\\' . __NAMESPACE__ . '\Rule\\' . $class;
            $name = strtolower(str_replace('\\', '', $class));
            $errorMessage = constant($fullClassName . '::MESSAGE');
            $labeledErrorMessage = constant($fullClassName . '::LABELED_MESSAGE');
            $this->ruleFactory->register($name, $fullClassName, $errorMessage, $labeledErrorMessage);
        }
    }

    public function add($selector, $name = null, $options = null, $messageTemplate = null, $label = null)
    {
        // the $selector is an associative array with $selector => $rules
        if (func_num_args() == 1) {
            if (!is_array($selector)) {
                throw new \InvalidArgumentException('If $selector is the only argument it must be an array');
            }

            return $this->addMultiple($selector);
        }

        // check if the selector is in the form of 'selector:Label'
        if (strpos($selector, ':') !== false) {
            list($selector, $label) = explode(':', $selector, 2);
        }

        $this->ensureSelectorRulesExist($selector);
        
        // remove existing rule [Added by kenjis]
        call_user_func(array($this->rules[$selector], 'remove'), $name, $options);
        
        call_user_func(array($this->rules[$selector], 'add'), $name, $options, $messageTemplate, $label);

        return $this;
    }

    public function validate($data = null)
    {
        if ($data !== null) {
            $this->setData($data);
        }
        // data was already validated, return the results immediately
        if ($this->wasValidated === true) {
            return $this->wasValidated && count($this->messages) === 0;
        }
        foreach ($this->rules as $selector => $valueValidator) {
            foreach ($this->getDataWrapper()->getItemsBySelector($selector) as $valueIdentifier => $value) {
                /* @var $valueValidator \Sirius\Validation\ValueValidator */
                if (!$valueValidator->validate($value, $valueIdentifier, $this->getDataWrapper())) {
                    foreach ($valueValidator->getMessages() as $message) {
                        $this->addMessage($valueIdentifier, $message);
                    }
                } else {
                    $this->validatedData[$valueIdentifier] = $value;
                }
            }
        }
        $this->wasValidated = true;

        return $this->wasValidated && count($this->messages) === 0;
    }

    protected function ensureSelectorRulesExist($selector)
    {
        if (!isset($this->rules[$selector])) {
            $this->rules[$selector] = new ValueValidator($this->getRuleFactory(), $this->getErroMessagePrototype());
        }
    }

    public function getValidated()
    {
        return $this->validatedData;
    }
}
