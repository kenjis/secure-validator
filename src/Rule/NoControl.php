<?php

namespace Kenjis\Validation\Rule;

use Sirius\Validation\Rule\AbstractRule;

class NoControl extends AbstractRule
{
    const MESSAGE = 'This input has control character';
    const LABELED_MESSAGE = '{label} has control character';

    public function validate($value, $valueIdentifier = null)
    {
        // does not have control characters except \r,\n,\t
        return (preg_match('/\A[\r\n\t[:^cntrl:]]*\z/u', $value) === 1);
    }
}
