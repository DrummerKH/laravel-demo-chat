<?php

Route::get("/", function()
{
    return View::make("index/index");
});

Route::controller('account','AccountController' );
Route::controller('msg','MessageController' );