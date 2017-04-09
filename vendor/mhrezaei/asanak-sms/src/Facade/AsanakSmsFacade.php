<?php

namespace Asanak\Sms\Facade;
use Illuminate\Support\Facades\Facade;

class AsanakSmsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AsanakSms';
    }
}