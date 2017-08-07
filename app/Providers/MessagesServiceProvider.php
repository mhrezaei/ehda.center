<?php

namespace App\Providers;

use App\Models\Message;
use Asanak\Sms\Facade\AsanakSms;
use Illuminate\Support\ServiceProvider;

class MessagesServiceProvider extends ServiceProvider
{
    private static $defaults = [
        'email' => [
            'subject'  => '',
            'template' => 'default_email',
        ]
    ];

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

    public static function storeMessages($data)
    {
        Message::store($data);
    }

    public static function sendPendingMessages($limit = 20)
    {
        $pendingMessages = self::getPendingMessages($limit);

        $pendingMessages->each(function ($message) {
            $message->spreadMeta();

            $messageType = $message->type;

            $functionName = 'send' . ucfirst($messageType);
            self::$functionName($message);
        });
    }

    private static function getPendingMessages($limit)
    {
        return Message::orderBy('id', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * @param Message $message
     */
    private static function sendSms($message)
    {
        $sendingSmsResult = AsanakSms::send($message->receiver, $message->content);
        $message->delete();
    }

    /**
     * @param Message $message
     */
    private static function sendEmail($message)
    {
        foreach (self::$defaults['email'] as $key => $default) {
            if (!$message->$key) {
                $message->$key = $default;
            }
        }

        $sendingEmailResult = EmailServiceProvider::send(
            $message->content,
            $message->receiver,
            setting()->ask('site_title')->gain(),
            $message->subject,
            $message->template
        );
        $message->delete();
    }
}
