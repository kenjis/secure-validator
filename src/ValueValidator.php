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

    public function remove($name = true, $options = null)
    {
        if ($name === true) {
            $this->rules = new RuleCollection();

            return $this;
        }

        // Added by kenjis
        if (is_array($name) && !is_callable($name)) {
            return $this->removeMultiple($name);
        }
        if (is_string($name)) {
            // rule was supplied like 'required | email'
            if (strpos($name, ' | ') !== false) {
                return $this->remove(explode(' | ', $name));
            }
            // rule was supplied like this 'length(2,10)(error message template)(label)'
            if (strpos($name, '(') !== false) {
                list ($name, $options, $messageTemplate, $label) = $this->parseRule($name);
            }
        }

        $validator = $this->ruleFactory->createRule($name, $options);
        $this->rules->detach($validator);

        return $this;
    }
}
