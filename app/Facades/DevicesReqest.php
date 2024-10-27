<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class DevicesReqest extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Services\DevicesReqest::class;
    }
}
