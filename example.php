<?php

$loader = require __DIR__ . '/vendor/autoload.php';

$validator = new \Kenjis\Validation\Validator;

$validator->add('a', 'required');
$validator->add('a', 'maxlength', ['max' => 60]);
$validator->remove('a', 'ValidUtf8');
$validator->add('b', 'maxlength', ['max' => 60]);
$validator->add('c', 'maxlength', ['max' => 60]);
$validator->add('d', 'maxlength', ['max' => 60]);
$validator->add('e', 'maxlength', ['max' => 60]);
$validator->add('f', 'maxlength', ['max' => 60]);

$_POST = [
    'a' => [
        0 => 'foo@example.jp',
        1 => rawurldecode('%E6%97%A5%E6%0C%AC%E8%AA%9E%E3%81%82%E3%81%84%E3%81%86%E3%81%88%E3%81%8A')   // invalid char encoding
    ],  // array
    'b' => rawurldecode('%001234'), // null byte
    'c' => rawurldecode('%0a1234'), // linefeed
    'd' => rawurldecode('%181234'), // controll char
    'f' => rawurldecode('%E6%97%A5%E6%0C%AC%E8%AA%9E%E3%81%82%E3%81%84%E3%81%86%E3%81%88%E3%81%8A'),    // invalid char encoding
];

if ($validator->validate($_POST)) {
    var_dump($validator->getValidated());
} else {
    $errors = $validator->getMessages();
    foreach ($errors as $key => $val) {
        foreach ($val as $error) {
            echo $key . ': ' . $error, PHP_EOL;
        }
    }
}
