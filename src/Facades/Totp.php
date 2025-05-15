<?php

namespace TotpGenerator\Facades;

use Illuminate\Support\Facades\Facade;
use TotpGenerator\Contracts\TotpGeneratorContract;

class Totp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TotpGeneratorContract::class;
    }
}