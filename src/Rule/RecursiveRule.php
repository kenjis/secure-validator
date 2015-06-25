<?php

namespace Kenjis\Validation\Rule;

use Sirius\Validation\Rule\AbstractRule;

abstract class RecursiveRule extends AbstractRule
{
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

        return $this->validateScalar($value, $valueIdentifier);
    }

    abstract protected function validateScalar($value, $valueIdentifier);
}
