<?php


Route::filter('auth-token', function()
{
    // Check if auth header was sent
    if (!Request::header('X-AUTH-KEY')) {
        return Response::make('Unauthorized', 401);
    }

    // Check if uuid is valid
    $user = DB::table('users')->where('uuid', Request::header('X-AUTH-KEY'))->first();
    if (!$user) {
        return Response::make('Unauthorized', 401);
    }

    // Store user in session for later (assumed array sessions)
    Session::put('user', $user);
});
