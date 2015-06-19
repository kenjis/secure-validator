<?php

namespace Kenjis\Validation\Rule;

use Sirius\Validation\Rule\AbstractRule;

class NoTabAndNewLine extends AbstractRule
{
    const MESSAGE = 'This input has tab or newline';
    const LABELED_MESSAGE = '{label} has tab or newline';

    public function validate($value, $valueIdentifier = null)
    {
        // does not have \r,\n,\t
        return (preg_match('/\A[^\r\n\t]*\z/u', $value) === 1);
    }
}
