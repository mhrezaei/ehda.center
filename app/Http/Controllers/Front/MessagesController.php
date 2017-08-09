<?php

namespace App\Http\Controllers\Front;

use App\Providers\MessagesServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessagesController extends Controller
{
    public function sendMessages($lang, $limit = null)
    {
        if (is_null($limit)) {
            MessagesServiceProvider::sendPendingMessages();
        } else {
            MessagesServiceProvider::sendPendingMessages($limit);
        }
    }
}
