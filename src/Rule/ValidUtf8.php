<?php

namespace Kenjis\Validation\Rule;

use Sirius\Validation\Rule\AbstractRule;

class ValidUtf8 extends AbstractRule
{
    const MESSAGE = 'This input is not a valid UTF-8 string';
    const LABELED_MESSAGE = '{label} is not a valid UTF-8 string';

    public function validate($value, $valueIdentifier = null)
    {
        if (is_array($value)) {
            $results = array_map([$this, 'validate'], $value);
            foreach ($results as $bool) {
                if (! $bool) {
                    return false;
                }
            }
            return true;
        }

        return mb_check_encoding($value, 'UTF-8');
    }
}
