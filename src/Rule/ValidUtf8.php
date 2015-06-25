<?php

namespace Kenjis\Validation\Rule;

use Sirius\Validation\Rule\AbstractRule;

class ValidUtf8 extends RecursiveRule
{
    const MESSAGE = 'This input is not a valid UTF-8 string';
    const LABELED_MESSAGE = '{label} is not a valid UTF-8 string';

    protected function validateScalar($value, $valueIdentifier)
    {
        return mb_check_encoding($value, 'UTF-8');
    }
}
