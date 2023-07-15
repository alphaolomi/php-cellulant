<?php

use Alphaolomi\Cellulant\ValidationUtility;

it('can validate', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'birthdate' => '1990-01-01',
        'website' => 'https://example.com',
        // 'start_date' => '2023-07-16 21:18:30',
        // 'end_date' => '2023-07-16 22:00:00',
    ];

    $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'birthdate' => 'required|date|after:1970-01-01',
        'website' => 'required|url',
        // 'start_date' => 'required|date_time_after:end_date',
        // 'end_date' => 'required|date:Y-m-d H:i:s',
    ];

    $validator = new ValidationUtility();
    $errors = $validator->validate($data, $rules);

    // if (!empty($errors)) {
    //     foreach ($errors as $field => $fieldErrors) {
    //         foreach ($fieldErrors as $error) {
    //             echo "Field '$field' failed validation rule: $error<br>";
    //         }
    //     }
    // } else {
    //     echo "Validation passed!";
    // }
    expect($errors)->toBe([]);
});
