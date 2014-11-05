<?php

use Illuminate\Support\Collection;

Route::get('/', function ()
{
    return 'Hello World!';
});

Route::post('/post/data', function ()
{
    $validation = Validator::make($data = Input::all(), [
        'username' => 'required',
        'password' => 'required'
    ]);

    if ($validation->fails())
    {
        throw new RuntimeException("Validation failed");
    }

    return Collection::make($data);
});