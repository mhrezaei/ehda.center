# Laravel Asanak Sms Package

[![Latest Stable Version](https://poser.pugx.org/mhrezaei/asanak-sms/v/stable)](https://packagist.org/packages/mhrezaei/AsanakSms)
[![Total Downloads](https://poser.pugx.org/mhrezaei/asanak-sms/downloads)](https://packagist.org/packages/mhrezaei/AsanakSms)
[![License](https://poser.pugx.org/mhrezaei/asanak-sms/license)](https://packagist.org/packages/mhrezaei/AsanakSms)


----------


**Installation**:

Run below statements on your terminal :

STEP 1 : 

    composer require "mhrezaei/asanak-sms":"1.0.0"
    
STEP 2 : Add `provider` and `facade` in config/app.php

    'providers' => [
      ...
      Asanak\Sms\AsanakSmsProvider::class, // <-- add this line at the end of provider array
    ],


    'aliases' => [
      ...
      'AsanakSms' => Asanak\Sms\Facade\AsanakSmsFacade::class, // <-- add this line at the end of aliases array
    ]

Step 3:  

    php artisan vendor:publish --tag=AsanakSmsTag --force

Configuration file is placed in config/asanak-sms.php , open it and enter your Asanak webservice config


**How to use!**:

        AsanakSms::send('mobile_number', 'massage_body');
