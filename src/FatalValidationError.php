<?php

namespace Kenjis\Validation;

use Sirius\Validation\Rule\AbstractRule;
use RuntimeException;

class FatalValidationError extends RuntimeException
{
    private $rule;
    private $value;
    private $valueIdentifier;

    public function setRule(AbstractRule $rule, $value, $valueIdentifier)
    {
        $this->rule = $rule;
        $this->value = $value;
        $this->valueIdentifier = $valueIdentifier;
    }

    public function getRule()
    {
        return $this->rule;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getvalueIdentifier()
    {
        return $this->valueIdentifier;
    }
}
