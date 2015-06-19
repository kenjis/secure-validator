<?php

namespace Kenjis\Validation\Rule;

use Sirius\Validation\Rule\AbstractRule;

class IsString extends AbstractRule
{
    const MESSAGE = 'This input is not a valid string';
    const LABELED_MESSAGE = '{label} is not a valid string';

    public function validate($value, $valueIdentifier = null)
    {
        return is_string($value);
    }
}
