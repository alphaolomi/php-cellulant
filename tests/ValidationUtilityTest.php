<?php

use Alphaolomi\Cellulant\ValidationUtility;

it('validates required field', function () {
    $validator = new ValidationUtility();

    $data = [
        'name' => 'John Doe',
        'email' => '',
    ];

    $rules = [
        'name' => 'required',
        'email' => 'required',
    ];

    $validateData = $validator->validate($data, $rules);

    expect($validateData)->toBeArray()->toHaveKey('name');
    expect($validator->getErrors())->toHaveKey('email');
});

it('validates nullable field', function () {
    $validator = new ValidationUtility();

    $data = [
        'name' => 'John Doe',
        'email' => null,
    ];

    $rules = [
        'name' => 'required',
        'email' => 'nullable',
    ];

    $validateData = $validator->validate($data, $rules);

    expect($validateData)->toBeArray()->toHaveKey('name');
    expect($validator->getErrors())->toBeEmpty();
});

it('validates string field', function () {
    $validator = new ValidationUtility();

    $data = [
        'name' => 'John Doe',
        'age' => 25,
    ];

    $rules = [
        'name' => 'string',
        'age' => 'string',
    ];

    $validateData = $validator->validate($data, $rules);

    expect($validateData)->toBeArray()->toHaveKey('name');
    expect($validator->getErrors())->toHaveKey('age');
});

it('validates date field', function () {
    $validator = new ValidationUtility();

    $data = [
        'start_date' => '2023-07-15',
        'end_date' => '2023-07-20',
    ];

    $rules = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    $validateData = $validator->validate($data, $rules);

    expect($validateData)->toBeArray()->toHaveKeys(['start_date', 'end_date']);
    expect($validator->getErrors())->toBeEmpty();
});

it('validates custom date format', function () {
    $validator = new ValidationUtility();

    $data = [
        'date' => '15/07/2023',
    ];

    $rules = [
        'date' => 'date:d/m/Y',
    ];

    $validateData = $validator->validate($data, $rules);

    expect($validateData)->toBeArray()->toHaveKey('date');
    expect($validator->getErrors())->toBeEmpty();
});

it('validates datetime field', function () {
    $validator = new ValidationUtility();

    $data = [
        'timestamp' => '2023-07-15 12:00:00',
    ];

    $rules = [
        'timestamp' => 'datetime',
    ];

    $validateData = $validator->validate($data, $rules);

    expect($validateData)->toBeArray()->toHaveKey('timestamp');
    expect($validator->getErrors())->toBeEmpty();
});

it('validates after date', function () {
    $validator = new ValidationUtility();

    $data = [
        'start_date' => '2023-07-20',
        'end_date' => '2023-07-25',
    ];

    $rules = [
        'start_date' => 'after:end_date',
        'end_date' => 'date',
    ];

    $validateData = $validator->validate($data, $rules);

    expect($validateData)->toBeArray()->toHaveKey('end_date');
    expect($validator->getErrors())->toHaveKey('start_date');
});

it('validates datetime after another datetime field', function () {
    $validator = new ValidationUtility();

    $data = [
        'start_date' => '2023-07-15 12:00:00',
        'end_date' => '2023-07-15 11:00:00',
    ];


    $rules = [
        'start_date' => 'datetime_after:end_date',
        'end_date' => 'datetime',
    ];
    $validateData = $validator->validate($data, $rules);

    expect($validateData)->toBeArray()->toHaveKey('end_date');
    expect($validator->getErrors())->toHaveKey('start_date');
});

it('validates URL field', function () {
    $validator = new ValidationUtility();

    $data = [
        'website' => 'https://example.com',
    ];

    $rules = [
        'website' => 'url',
    ];

    $validateData = $validator->validate($data, $rules);

    expect($validateData)->toBeArray()->toHaveKey('website');
    expect($validator->getErrors())->toBeEmpty();
});

it('validates phone number', function () {
    $validator = new ValidationUtility();

    $data = [
        'phone' => '0123456789',
    ];

    $rules = [
        'phone' => 'phone',
    ];

    $validateData = $validator->validate($data, $rules);

    expect($validateData)->toBeArray()->toHaveKey('phone');
    expect($validator->getErrors())->toBeEmpty();
});
