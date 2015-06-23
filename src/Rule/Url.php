<?php

namespace Kenjis\Validation\Rule;

use Sirius\Validation\Rule\AbstractRule;

class Url extends AbstractRule
{
    const MESSAGE = 'This input is not a valid URL';
    const LABELED_MESSAGE = '{label} is not a valid URL';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;

        if (empty($value)) {
            return false;
        } if (preg_match('/\Ahttp(s?):/i', $value) !== 1) {
            return false;
        }

        return (filter_var($value, FILTER_VALIDATE_URL) !== false);
    }
}
