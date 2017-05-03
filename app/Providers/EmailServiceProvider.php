<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public static function send($msgBody, $to, $name, $subject, $template)
    {
//        $user['data'] = $msgBody->toArray();
        $data['text'] = $msgBody;
        Mail::send('templates.email.' . $template, $data, function ($m) use ($to, $name, $subject) {
            $m->from(env('MAIL_FROM'), trans('front.site_title'));

            $m->to($to, $name)
                ->subject($subject);
        });
    }
}
