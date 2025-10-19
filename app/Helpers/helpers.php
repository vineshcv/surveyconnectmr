<?php

use Illuminate\Support\Str;

if (! function_exists('generate_username')) {
    function generate_username($firstName, $lastName)
    {
        return Str::slug($firstName . '.' . $lastName) . rand(100, 999);
    }
}
