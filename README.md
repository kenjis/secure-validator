# Secure Validator

Secure Validator is a library for data validation. It is based on [Sirius Validation](https://github.com/siriusphp/validation).

## Requirements

* PHP 5.4.0 or later

## Features

### Default Rules

Secure Validator promotes strict validation. It provides default validtion rules.

 * checks if value is valid UTF-8 character encoding
 * checks if value is string
 * checks if value does not have control characters (except for tab and newline)

And

 * adds max length 1 letter.

That is you have to set (override) max length rule to all fields. You don't forget it.

If a field does not match the default rules, you can remove the rules.

### Validated Data

You can get validated data only with `$validator->getValidated()`.
