<?php

return [

    'feeds' => [
        [
            'items' => ['App\Models\Post@getFeedItems' , 'fa' ],
            'url' => '/feed/fa'  ,
            'title' => 'سامانه اهدای عضو ایرانیان' // ,setting()->ask('site_title')->in('fa')->gain(),
        ],
        //[
        //    'items' => ['App\Models\Post@getFeedItems' , 'en' ],
        //    'url' => '/feed/en'  ,
        //    'title' => setting()->ask('site_title')->in('en')->gain(),
        //],
    ],

];
