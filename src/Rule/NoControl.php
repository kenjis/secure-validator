<?php

namespace Kenjis\Validation\Rule;

use Sirius\Validation\Rule\AbstractRule;

class NoControl extends AbstractRule
{
    const MESSAGE = 'This input has control character';
    const LABELED_MESSAGE = '{label} has control character';

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

        // does not have control characters except \r,\n,\t
        return (preg_match('/\A[\r\n\t[:^cntrl:]]*\z/u', $value) === 1);
    }
}
