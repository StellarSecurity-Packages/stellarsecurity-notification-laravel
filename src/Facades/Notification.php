<?php

namespace StellarSecurity\Notifications\Facades;

use Illuminate\Support\Facades\Facade;

class Notification extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'stellar.notification';
    }
}
