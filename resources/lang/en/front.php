<?php

return [
    'persian'                                  => 'Persian',
    'english'                                  => 'English',
    'arabic'                                   => 'Arabic',
    'login_register'                           => 'Register / Login',
    'orders'                                   => 'Orders',
    'profile'                                  => 'Edit profile',
    'setting'                                  => 'Setting',
    'log_out'                                  => 'Logout',
    'home'                                     => 'Home',
    'about'                                    => 'About',
    'products'                                 => 'Products',
    'posts'                                    => 'Posts',
    'contact_us'                               => 'Contact us',
    'categories'                               => 'Categories',
    'categories_of'                            => 'Categories of',
    'show_products'                            => 'See Products',
    'drawing_code_register'                    => 'Register Drawing Code',
    'check_code'                               => 'Check Code',
    'drawing_check_code_fail'                  => 'The code you entered is not valid',
    'login'                                    => 'Login',
    'not_member_register_now'                  => 'Not a member? Register now',
    'register'                                 => 'Register',
    'member_login'                             => 'Are you member? Please Login',
    'code_melli_already_exists'                => 'The National Code already exist',
    'relogin'                                  => 'Your information is available on the site, please log in.',
    'register_failed'                          => 'Registration error occurred, please try again.',
    'register_success'                         => 'Registration was successful, wait.',
    'register_success_sms'                     => 'You have successfully registered in ::site',
    'register_success_email'                   => 'You have successfully registered in ::site',
    'register_code_success_sms'                => "Hello ::name,\n\rYour drawing code has successfully registered.",
    'all_user_score'                           => 'Points collected:',
    'edit_profile'                             => 'Edit profile',
    'accepted_codes'                           => 'Registered codes',
    'events'                                   => 'Events',
    'running_events'                           => 'Running Events',
    'expired_events'                           => 'Expired Events',
    'soon'                                     => 'Soon',
    'add_comment'                              => 'Add Comment',
    'sex'                                      => 'Gender',
    'marital'                                  => 'Marital status',
    'single'                                   => 'Single',
    'married'                                  => 'married',
    'drawing_code_success_receive_please_wait' => 'Code is accepted, think for a moment.',
    'drawing_code_fail_receive'                => 'Code is repeated.',
    'add_code'                                 => 'Add Code',
    'code'                                     => 'Code',
    'created_at'                               => 'Register date',
    'purchased_at'                             => 'Invoice date',
    'price'                                    => 'price',
    'drawing_code_not_found'                   => 'The code has not been registered so far.',
    'rials'                                    => 'Rials',
    'sort'                                     => 'Sort',
    'price_max_to_min'                         => 'Price Many > Low',
    'price_min_to_max'                         => 'Price Low > Many',
    'best_seller'                              => 'Best seller',
    'favorites'                                => 'Favorites',
    'search'                                   => 'Search',
    'toman'                                    => 'Toman',
    'from'                                     => 'from',
    'to'                                       => 'to',
    'states'                                   => 'States',
    'gallery'                                  => 'Gallery',
    'news'                                     => 'News',
    'hot_news'                                 => 'Hot News',
    'ehda_news'                                => 'Organ Donation News',
    'faqs'                                     => 'Faqs',
    'faq_not_found_ask_yours'                  => 'Didn\'t find your answer? Ask your question.',
    'read_more'                                => 'Read More',
    'continue'                                 => 'Continue',
    'more'                                     => 'More',
    'teammates'                                => 'Teammates',
    'no_result_found'                          => 'No Results Found',
    'view_on_map'                              => 'View on Map',
    'volunteers'                               => 'Volunteers',
    'special_volunteers'                       => 'Special Volunteers',
    'send_works'                               => 'Send Works',
    'send_work'                                => 'Send Work',
    'personal_information'                     => 'Personal Information',
    'educational_information'                  => 'Educational Information',
    'welcome_message'                          => 'Welcome',
    'archive'                                  => 'Archive',
    'short_link'                               => 'Short Link',
    'contact_info'                             => 'Contact Info',
    'login_info'                               => 'Login Info',

    'volunteer_section' => [
        'singular'         => 'Organ Donation Volunteer',
        'plural'           => 'Organ Donation Volunteers',
        'section'          => 'Organ Donation Volunteers Section',
        'register'         => 'Register Volunteer',
        'register_success' => 'Volunteer registered successfuly.',
        'special'          => [
            'singular' => 'Special Volunteer',
            'plural'   => 'Special Volunteers',
        ],
    ],

    'organ_donation_card_section' => [
        'singular' => 'Organ Donation Card',
        'print'    => 'Print Organ Donation Card',
        'download' => 'Save Organ Donation Card',
        'preview'  => 'Preview Organ Donation Card',
        'card'     => 'Card',
        'partial'  => [
            'part1' => 'Organ',
            'part2' => 'Donation',
            'part3' => 'Card',
        ],
    ],

    'angels' => [
        'plural'   => 'Angels',
        'singular' => 'Angel',
    ],

    'member_section' => [
        'profile_edit' => 'Edit Profile',
        'sign_in'      => 'Sing In',
        'sign_out'     => 'Sign Out',
    ],

    'profile_phrases' => [
        'not_enough_information'   => 'Not Enough Profile Information',
        'complete_to_join_drawing' => 'Now you can join the drawing by completing your profile.',
        'welcome_user'             => 'Welcome ::user.',
        'profile'                  => 'Profile',
        'user_profile'             => ':user Profile',
    ],

    'file_types' => [
        'image' => [
            'title'         => 'Image',
            'dropzone_text' => 'Drop your images here.'
        ],
        'text'  => [
            'title'         => 'Text',
            'dropzone_text' => 'Drop your text files here.'
        ],
        'video' => [
            'title'         => 'Video',
            'dropzone_text' => 'Drop your video files here.'
        ],
        'audio' => [
            'title'         => 'Audio',
            'dropzone_text' => 'Drop your audio files here.'
        ],
    ],

    'upload' => [
        'errors' => [
            'size'   => 'File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.',
            'type'   => 'You can\'t upload files of this type.',
            'server' => 'Server responded with {{statusCode}} code.',
            'limit'  => 'You can not upload any more files.',
        ],
    ],

    'footer' => [
        'copy_right' => 'All rights reserved for ::site.',
        'created_by' => 'Prepared by: ',
        'yasna_team' => 'Yasna Team'
    ],

    'messages' => [
        'you_are_volunteer'       => 'You are volunteer.',
        'you_are_card_holder'     => 'You have organ donation card.',
        'unable_to_register_card' => 'Unable to Register Card',
        'login'                   => 'Login.',
    ],

    'notes' => [
        'moments_are_important_to_save_life' => 'Every moment is important to save <span class="text-success">life</span>.',
        'one_brain_dead_can_save_8_lives'    => 'One brain dead can save 8 lives.',
        'organ_donation'                     => 'Organ Donation',
        'life_donation'                      => 'Life Donation',
        'follow_us_in_social'                => 'Follow us in social networks.',
    ],

    "main-menu" => [
        'items' => [
            'learn'   => 'to Learn',
            'will'    => 'to Will',
            'achieve' => 'to Achieve',
            'join'    => 'to Join',
        ]
    ],
];