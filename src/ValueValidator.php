<?php

namespace Kenjis\Validation;

use Sirius\Validation\RuleFactory;
use Sirius\Validation\ErrorMessage;
use Sirius\Validation\RuleCollection;
use Sirius\Validation\DataWrapper\WrapperInterface;
use Sirius\Validation\Rule\Required;

class ValueValidator extends \Sirius\Validation\ValueValidator
{
    private $isRequired;

    /**
     * Added by kenjis
     * 
     * @param array $rules [0 => [ruleNameString, optionArray], ...]
     */
    public function addDefaultRules(array $rules)
    {
        foreach ($rules as $rule) {
            isset($rule[1]) ?: $rule[1] = null;
            $this->add($rule[0], $rule[1]);
        }
    }

    /**
     * Added by kenjis
     */
    public function removeMultiple($rules)
    {
        foreach ($rules as $singleRule) {
            // make sure the rule is an array (the parameters of subsequent calls);
            $singleRule = is_array($singleRule) ? $singleRule : array(
                $singleRule
            );
            call_user_func_array(
                array(
                    $this,
                    'remove'
                ),
                $singleRule
            );
        }
    }

    /**
     * Remove rule
     * 
     * @param mixed $name    rule name or true if all rules should be deleted for that selector
     * @param mixed $options rule options, necessary for rules that depend on params for their ID
     * @return \Kenjis\Validation\ValueValidator
     */
    public function remove($name = true, $options = null)
    {
        if ($name === true) {
            $this->rules = new RuleCollection();
            return $this;
        }

        if (is_callable($name)) {
            return $this->removeRule($name, $options);
        } elseif (is_array($name)) {
            return $this->removeMultiple($name);
        }

        return $this->removeByString($name, $options);
    }

    protected function removeByString($name, $options)
    {
        // rule was supplied like 'required | email'
        if (strpos($name, ' | ') !== false) {
            return $this->remove(explode(' | ', $name));
        }

        // rule was supplied like this 'length(2,10)(error message template)(label)'
        if (strpos($name, '(') !== false) {
            list($name, $options,,) = $this->parseRule($name);
        }

        $this->removeRule($name, $options);
        return $this;
    }

    protected function removeRule($name, $options)
    {
        $validator = $this->ruleFactory->createRule($name, $options);
        $this->rules->detach($validator);
    }

    public function validate($value, $valueIdentifier = null, WrapperInterface $context = null)
    {
        $this->messages = array();

        if (! $this->isRequired() && $value === null) {
            return true;
        }

        $this->runValidations($value, $valueIdentifier, $context);

        return ! $this->hasError();
    }

    protected function hasError()
    {
        return count($this->messages) !== 0;
    }

    protected function isRequired()
    {
        if ($this->isRequired !== null) {
            return $this->isRequired;
        }

        $this->isRequired = false;
        foreach ($this->rules as $rule) {
            if ($rule instanceof Required) {
                $this->isRequired = true;
                break;
            }
        }
        return $this->isRequired;
    }

    protected function runValidations($value, $valueIdentifier, $context)
    {
        /* @var $rule \Sirius\Validation\Rule\AbstractValidator */
        foreach ($this->rules as $rule) {
            $rule->setContext($context);
            $this->runValidation($rule, $value, $valueIdentifier);

            // if field is required and we have an error,
            // do not continue with the rest of rules
            if ($this->isRequired() && $this->hasError()) {
                break;
            }
        }
    }

    protected function runValidation($rule, $value, $valueIdentifier)
    {
        if (!$rule->validate($value, $valueIdentifier)) {
            // if fatal rule fails
            if ($rule->getOption('fatal')) {
                $exception = new FatalValidationError($rule->getMessage());
                $exception->setRule($rule, $value, $valueIdentifier);
                throw $exception;
            }

            $this->addMessage($rule->getMessage());
        }
    }
}
