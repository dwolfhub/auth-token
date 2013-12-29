<?php

use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;

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
    $userQuery = DB::table('users');
    $userQuery = $userQuery->where($identifierColumn, $identifier);
    $user = $userQuery->first(array('uuid', 'password'));

    // Make sure password is valid
    if (!Hash::check($password, $user->password)) {
        return Response::make('Unauthorized', 401);
    }

    // Update uuid
    $uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, $email . date('Ymd'))->toString();
    $userQuery->update(array('uuid' => $uuid));

    // Return UUID
    return Response::json(array(
        'key' => $uuid
    ));
});
