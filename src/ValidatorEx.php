<?php

namespace Kenjis\Validation;

use Sirius\Validation\RuleFactory;

class ValidatorEx extends \Sirius\Validation\Validator
{
    protected $validatedData = [];

    public function __construct(RuleFactory $ruleFactory = null, ErrorMessage $errorMessagePrototype = null)
    {
        parent::__construct($ruleFactory, $errorMessagePrototype);

        // register rules [Added by kenjis]
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
            return count($this->messages) === 0;
        }

        foreach ($this->rules as $selector => $valueValidator) {
            foreach ($this->getDataWrapper()->getItemsBySelector($selector) as $valueIdentifier => $value) {
                /* @var $valueValidator \Kenjis\Validation\ValueValidator */
                if (!$valueValidator->validate($value, $valueIdentifier, $this->getDataWrapper())) {
                    $this->addErrorMessages($valueValidator, $valueIdentifier);
                } else {
                    $this->setValidatedData($valueIdentifier, $value);
                }
            }
        }
        $this->wasValidated = true;

        return count($this->messages) === 0;
    }

    protected function addErrorMessages($valueValidator, $valueIdentifier)
    {
        foreach ($valueValidator->getMessages() as $message) {
            $this->addMessage($valueIdentifier, $message);
        }
    }

    protected function setValidatedData($valueIdentifier, $value)
    {
        // handle array
        if (preg_match('/(.+)\[(.+)\]\[(.+)\]/i', $valueIdentifier, $matches)) {
            $name = $matches[1];
            $key1 = $matches[2];
            $key2 = $matches[3];
            $this->validatedData[$name][$key1][$key2] = $value;
        } else {
            $this->validatedData[$valueIdentifier] = $value;
        }
    }

    protected function ensureSelectorRulesExist($selector)
    {
        if (!isset($this->rules[$selector])) {
            $this->rules[$selector] = new ValueValidator($this->getRuleFactory(), $this->getErroMessagePrototype());
        }
    }

    public function getValidated($item = null)
    {
        if ($item) {
            return isset($this->validatedData[$item]) ? $this->validatedData[$item] : null;
        }
        return $this->validatedData;
    }

    public function getInputValue($item)
    {
        return $this->getDataWrapper()->getItemValue($item);
    }
}
