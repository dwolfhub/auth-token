<?php


Route::filter('auth-token', function()
{
    if (!isset(Request::server('X-AUTH-KEY'))) {
        return Response::make('Unauthorized', 401);
    }


});
