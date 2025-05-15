<?php

return [

    /*
    |--------------------------------------------------------------------------
    | TOTP Default Period
    |--------------------------------------------------------------------------
    |
    | This is the default time step size (in seconds) for TOTP generation.
    |
    */

    'default_period' => 30,

    /*
    |--------------------------------------------------------------------------
    | TOTP Default Digits
    |--------------------------------------------------------------------------
    |
    | This is the default number of digits for the generated TOTP code.
    |
    */

    'default_digits' => 6,

    /*
    |--------------------------------------------------------------------------
    | TOTP Verification Window
    |--------------------------------------------------------------------------
    |
    | The number of periods before and after the current period to verify the code.
    |
    */

    'verification_window' => 1,

];