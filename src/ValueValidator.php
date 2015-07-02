<?php

namespace Kenjis\Validation;

use Sirius\Validation\RuleFactory;
use Sirius\Validation\ErrorMessage;
use Sirius\Validation\RuleCollection;

class ValueValidator extends \Sirius\Validation\ValueValidator
{
    public function __construct(RuleFactory $ruleFactory, ErrorMessage $errorMessagePrototype, $label = null)
    {
        $this->ruleFactory = $ruleFactory;
        $this->errorMessagePrototype = $errorMessagePrototype;
        if ($label) {
            $this->label = $label;
        }
        $this->rules = new RuleCollection;
        
        // add default rules [Added by kenjis]
        $this->add('IsString');
        $this->add('ValidUtf8');
        $this->add('NoControl');
        $this->add('maxlength', ['max' => 1]);
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
}
