# Secure Validator

[![Latest Stable Version](https://poser.pugx.org/kenjis/secure-validator/v/stable)](https://packagist.org/packages/kenjis/secure-validator) [![Total Downloads](https://poser.pugx.org/kenjis/secure-validator/downloads)](https://packagist.org/packages/kenjis/secure-validator) [![Latest Unstable Version](https://poser.pugx.org/kenjis/secure-validator/v/unstable)](https://packagist.org/packages/kenjis/secure-validator) [![License](https://poser.pugx.org/kenjis/secure-validator/license)](https://packagist.org/packages/kenjis/secure-validator)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kenjis/secure-validator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kenjis/secure-validator/?branch=master)
[![Coverage Status](https://coveralls.io/repos/kenjis/secure-validator/badge.svg?branch=master)](https://coveralls.io/r/kenjis/secure-validator?branch=master)
[![Build Status](https://travis-ci.org/kenjis/secure-validator.svg?branch=master)](https://travis-ci.org/kenjis/secure-validator)

Secure Validator is a library for input validation. It is based on [Sirius Validation](https://github.com/siriusphp/validation).

## Requirements

* PHP 5.4.0 or later

## Features

### Default Rules

Secure Validator promotes strict validation. It sets default validtion rules to all fields.

 * `ValidUtf8` checks if value is valid UTF-8 character encoding
 * `IsString` checks if value is string
 * `NoControl` checks if value does not have control characters (except for tab and newline)

And

 * adds `MaxLength` 1 letter.

That is you have to set (overwrite) max length rule to all fields. You don't forget it.

If a field does not match the default rules, you can remove the rules.

### Validated Data

You can get validated data only with `$validator->getValidated()`.

## How to Use

See [example.php](example.php) and [Sirius Validation Documentation](http://www.sirius.ro/php/sirius/validation/).

~~~php
$validator = new \Kenjis\Validation\Validator;
$validator->add('field', 'required | maxlength(max=60)');
if ($validator->validate($_POST)) {
    // validation passed
} else {
    // validation failed
}
~~~

### Added Method

#### `Validator::filter()`

Add filtering rule of Sirius\Filtration. See [Built-in filters](https://github.com/siriusphp/filtration/blob/master/docs/filters.md).

Validator will apply filters before validation.

~~~php
$validator->filter('field', 'StringTrim');
~~~

#### `Validator::getValidated()`

Get validated values.

~~~php
$allData = $validator->getValidated();

$field = $validator->getValidated('field');
~~~

#### `Validator::getInputValue()`

Get input value after filtering of specific field.

~~~php
$field = $validator->getInputValue('field');
~~~

### Changed Rules

`Url`: values must be a valid URL address (http, https only)

### Tips

When you set `required`, if a rule fails, Sirius Validation will not apply any more rules to that field.
