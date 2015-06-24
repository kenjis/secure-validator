# Secure Validator

Secure Validator is a library for data validation. It is based on [Sirius Validation](https://github.com/siriusphp/validation).

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

### Added Method

#### `Validator::filter()`

Add filtering rule of Sirius\Filtration. See [Built-in filters](https://github.com/siriusphp/filtration/blob/master/docs/filters.md).

Validator will apply filters before validation.

#### `Validator::getInputValue()`

Get raw input value of specific field.

### Changed Rules

`Url`: values must be a valid URL address (http, https only)

### Tips

When you set `required`, if a rule fails, Sirius Validation will not apply any more rules to that field.
