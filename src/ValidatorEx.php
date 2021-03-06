<?php

namespace Kenjis\Validation;

use Sirius\Validation\RuleFactory;

class ValidatorEx extends \Sirius\Validation\Validator
{
    protected $validatedData = [];
    protected $defaultRules = [];

    public function __construct(RuleFactory $ruleFactory = null, ErrorMessage $errorMessagePrototype = null)
    {
        parent::__construct($ruleFactory, $errorMessagePrototype);

        // register rules [Added by kenjis]
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

    public function setDefaultRules(array $rules)
    {
        $this->defaultRules = $rules;
        return $this;
    }

    /**
     * Add Rules
     * 
     * @param string|array $selector
     * @param string|callable $name
     * @param string|array $options
     * @param string $messageTemplate
     * @param string $label
     * 
     * @return \Kenjis\Validation\ValidatorEx
     * @throws \InvalidArgumentException
     */
    public function add($selector, $name = null, $options = null, $messageTemplate = null, $label = null)
    {
        // the $selector is an associative array with $selector => $rules
        if (func_num_args() == 1) {
            if (!is_array($selector)) {
                throw new \InvalidArgumentException(
                    'If $selector is the only argument it must be an array'
                );
            }

            return $this->addMultiple($selector);
        }

        return $this->addRule($selector, $name, $options, $messageTemplate, $label);
    }

    protected function addRule($selector, $name, $options, $messageTemplate, $label)
    {
        // check if the selector is in the form of 'selector:Label'
        if (strpos($selector, ':') !== false) {
            list($selector, $label) = explode(':', $selector, 2);
        }

        $this->ensureSelectorRulesExist($selector, $label);
        
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

        $this->validateValue();

        return count($this->messages) === 0;
    }

    protected function validateValue()
    {
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

    protected function ensureSelectorRulesExist($selector, $label = NULL)
    {
        if (!isset($this->rules[$selector])) {
            $valueValidator = new ValueValidator($this->getRuleFactory(), $this->getErroMessagePrototype(), $label);
            $valueValidator->addDefaultRules($this->defaultRules);
            $this->rules[$selector] = $valueValidator;
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
