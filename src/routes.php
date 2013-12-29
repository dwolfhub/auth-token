<?php

// Login route
Route::post('/login', function ()
{
    // Gather inputs
    $email = Input::get('email', false);
    $username = Input::get('username', false);
    $password = Input::get('password');

    // Make sure username or password was entered
    if ($email === false and $username === false) {
        return Response::make('Unauthorized', 401);
    }

    // Determine whether to use email or username as identifier
    $identifier = ($email === false)? $username: $email;
    $identifierColumn = ($email === false)? 'username': 'email';

    // Find user
    $user = DB::table('users');
    $user = $user->where($identifierColumn, $identifier);
    $user = $user->first(array('uuid', 'password'));

    // Make sure password is valid
    if (!Hash::check($password, $user->password)) {
        return Response::make('Unauthorized', 401);
    }

    // Return UUID
    return Response::json(array(
        'key' => $user->uuid
    ));
});
