<?php

namespace Kenjis\Validation\Rule;

use Sirius\Validation\Rule\AbstractRule;

/**
 * Validate URL
 * 
 * This logic is from CodeIgniter 3.0.0 <https://github.com/bcit-ci/CodeIgniter/blob/3fe79499c5bedb5b3bc4281821776f031f73674e/system/libraries/Form_validation.php#L1203>
 * 
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
class Url extends AbstractRule
{
    const MESSAGE = 'This input is not a valid URL';
    const LABELED_MESSAGE = '{label} is not a valid URL';

    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;

        if (empty($value)) {
            return false;
        } elseif (preg_match('/^(?:([^:]*)\:)?\/\/(.+)$/', $value, $matches)) {
            if (empty($matches[2])) {
                return false;
            } elseif ( ! in_array($matches[1], array('http', 'https'), true)) {
                return false;
            }

            $value = $matches[2];
        }

        $value = 'http://'.$value;

        // There's a bug affecting PHP 5.2.13, 5.3.2 that considers the
        // underscore to be a valid hostname character instead of a dash.
        // Reference: https://bugs.php.net/bug.php?id=51192
        if (
            version_compare(PHP_VERSION, '5.2.13', '==')
            OR version_compare(PHP_VERSION, '5.3.2', '==')
        ) {
            sscanf($value, 'http://%[^/]', $host);
            $value = substr_replace(
                $value,
                strtr($host, array('_' => '-', '-' => '_')),
                7,
                strlen($host)
            );
        }

        return (filter_var($value, FILTER_VALIDATE_URL) !== false);
    }
}
