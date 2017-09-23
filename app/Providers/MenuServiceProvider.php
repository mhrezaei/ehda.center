<?php

namespace App\Providers;

use App\Models\Posttype;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    // The locale that have been used in manage and should be used in queries
    private static $defaultLocale = 'fa';


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

    /**
     * Generates array of menu from post types and their categories
     *
     * @return array
     */
    public static function getMenuArray()
    {
        $menu = trans('front.main-menu.items');
        foreach ($menu as $key => $menuItem) {
            $tmpGroup = [
                'children' => [],
                'title'    => $menuItem,
            ];

            $postTypes = Posttype::where([
                'header_title' => trans('front.main-menu.items.' . $key, [], self::$defaultLocale),
            ])->get();

            if ($postTypes->count()) {
                $tmpGroup['title'] = $menuItem;

                foreach ($postTypes as $postType) {
                    $tmpColumn = [
                        'children' => [],
                        'title'    => $postType->titleIn(getLocale()),
                    ];

                    $folder = $postType->folders()
                        ->where([
                            'slug'   => 'no',
                            'locale' => getLocale(),
                        ])->first();
                    if ($folder and $folder->exists) {

                        $categories = $folder->categories;
                        foreach ($categories as $category) {
                            $tmpColumn['children'][] = [
                                'title' => $category->title,
                                'link'  => url_locale(implode(DIRECTORY_SEPARATOR, [
                                    'archive',
                                    $postType->slug,
                                    $category->slug,
                                ])),
                            ];
                        }
                    }

                    $tmpGroup['children'][] = $tmpColumn;
                }

                $menu[$key] = $tmpGroup;
            } else {
                unset($menu[$key]);
            }

        }

        return $menu;
    }

    /**
     * Returns a hard coded array for menu
     *
     * @return array
     */
    public static function getStaticMenuArray()
    {
        return [
            'learn'   => [
                'title'    => trans('front.main-menu.items.learn'),
                'children' => [
                    [
                        'title'    => trans('front.main-menu.sub_menus.learn.world_news'),
                        'children' => [
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.world_procurement'),
                                'link'  => route_locale('post.archive', [
                                    'postType' => 'word-news',
                                    'category' => 'world-opu-transplant',
                                ]),
                            ],
                        ],
                    ],
                    [
                        'title'    => trans('front.main-menu.sub_menus.learn.iran_news'),
                        'children' => [
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.iran_procurement'),
                                'link'  => route_locale('post.archive', [
                                    'postType' => 'iran-news',
                                    'category' => 'iran-opu-transplant',
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.internal-ngo'),
                                'link'  => route_locale('post.archive', [
                                    'postType' => 'iran-news',
                                    'category' => 'internal-ngo',
                                ]),
                            ],
                        ],
                    ],
                    [
                        'title'    => trans('front.main-menu.sub_menus.learn.general_educations'),
                        'children' => [
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.brain_death'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'brain-death',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.learn.brain_death')),
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.organ_donation'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'organ-donation',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.learn.organ_donation')),
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.allocation'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'allocation',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.learn.allocation')),
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.organ_transplant'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'organ-transplant',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.learn.organ_transplant')),
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.organ_transplant_history'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'organ-transplant-history',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.learn.organ_transplant_history')),
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.statistics'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'statistics',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.learn.statistics')),
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.faq'),
                                'link'  => url_locale('faq')
                            ],
                        ],
                    ],
                    [
                        'title'    => trans('front.main-menu.sub_menus.learn.professional_educations'),
                        'children' => [
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.educations_courses'),
                                'link'  => route_locale('education.archive', [
                                    'educationType' => 'courses'
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.educations_video'),
                                'link'  => route_locale('education.archive', [
                                    'educationType' => 'video'
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.educations_text'),
                                'link'  => route_locale('education.archive', [
                                    'educationType' => 'text'
                                ]),
                            ],
                        ],
                    ],
                    [
                        'title'    => trans('front.main-menu.sub_menus.learn.cultural'),
                        'children' => [
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.organ_donation_in_religion'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'organ-donation-in-religion',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.learn.organ_donation_in_religion')),
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.organ_donation_in_another_country'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'organ-donation-in-another-country',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.learn.organ_donation_in_another_country')),
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.learn.organ_donation_card'),
                                'link'  => route_locale('register_card'),
                            ],
                        ],
                    ],
                ]
            ],
            'will'    => [
                'title'    => trans('front.main-menu.items.will'),
                'children' => [
                    [
                        'title' => trans('front.main-menu.sub_menus.will.donations'),
                        'link'  => route_locale('post.single', [
                            'identifier' => 'donations',
                            'url'        => urlencode(trans('front.main-menu.sub_menus.will.donations')),
                        ]),
                    ],
                    [
                        'title'    => trans('front.main-menu.sub_menus.will.volunteers'),
                        'children' => [
                            [
                                'title' => trans('front.main-menu.sub_menus.will.special_volunteers'),
                                'link'  => route_locale('volunteers.special'),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.will.organ_donation_volunteers'),
                                'link'  => route_locale('volunteer.register.step.1.get'),
                            ],
                        ]
                    ],
                    [
                        'title' => trans('front.main-menu.sub_menus.will.participation_in_the_notification'),
                        'link'  => route_locale('post.single', [
                            'identifier' => 'participation-in-the-notification',
                            'url'        => urlencode(trans('front.main-menu.sub_menus.will.participation_in_the_notification')),
                        ]),
                    ],
                    [
                        'title' => trans('front.main-menu.sub_menus.will.supporters'),
                        'link'  => route_locale('post.single', [
                            'identifier' => 'supporters',
                            'url'        => urlencode(trans('front.main-menu.sub_menus.will.supporters')),
                        ]),
                    ],
                    [
                        'title'    => trans('front.main-menu.sub_menus.will.you_say'),
                        'children' => [
                            [
                                'title' => trans('front.main-menu.sub_menus.will.your_works'),
                                'link'  => route_locale('users.works.send'),
                            ],
//                            [
//                                'title' => trans('front.main-menu.sub_menus.will.your_memories'),
//                                'link'  => '#',
//                            ],
//                            [
//                                'title' => trans('front.main-menu.sub_menus.will.suggestions'),
//                                'link'  => '#',
//                            ],
                        ]
                    ],

                ]
            ],
            'achieve' => [
                'title'    => trans('front.main-menu.items.achieve'),
                'children' => [
                    [
                        'title'    => trans('front.main-menu.sub_menus.achieve.about_us'),
                        'children' => [
                            [
                                'title' => trans('front.main-menu.sub_menus.achieve.ngo_history'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'ngo-history',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.achieve.ngo_history')),
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.achieve.activities'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'activities',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.achieve.activities')),
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.achieve.board_of_directories'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'board-of-directories',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.achieve.board_of_directories')),
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.achieve.board_of_trustees'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'board-of-trustees',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.achieve.board_of_trustees')),
                                ]),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.achieve.founding'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'founding',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.achieve.founding')),
                                ]),
                            ],
//                            [
//                                'title' => trans('front.main-menu.sub_menus.achieve.organizational_chart'),
//                                'link'  => route_locale('post.single', [
//                                    'identifier' => 'organizational_chart',
//                                    'url'        => urlencode(trans('front.main-menu.sub_menus.achieve.organizational_chart')),
//                                ]),
//                            ],
//                            [
//                                'title' => trans('front.main-menu.sub_menus.achieve.statute'),
//                                'link'  => route_locale('post.single', [
//                                    'identifier' => 'statute',
//                                    'url'        => urlencode(trans('front.main-menu.sub_menus.achieve.statute')),
//                                ]),
//                            ],
//                            [
//                                'title' => trans('front.main-menu.sub_menus.achieve.tasks_goals'),
//                                'link'  => route_locale('post.single', [
//                                    'identifier' => 'tasks_goals',
//                                    'url'        => urlencode(trans('front.main-menu.sub_menus.achieve.tasks_goals')),
//                                ]),
//                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.achieve.committees'),
                                'link'  => route_locale('post.single', [
                                    'identifier' => 'committees',
                                    'url'        => urlencode(trans('front.main-menu.sub_menus.achieve.committees')),
                                ]),
                            ],
                        ]
                    ],
                    [
                        'title'    => trans('front.main-menu.sub_menus.achieve.gallery'),
                        'children' => [
                            [
                                'title' => trans('front.main-menu.sub_menus.achieve.pictures'),
                                'link'  => route_locale('gallery.categories', [
                                    'postType' => 'gallery',
                                ])
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.achieve.films'),
                                'link'  => route_locale('gallery.categories', [
                                    'postType' => 'films',
                                ])
                            ],
                            [
                                'title' => trans('front.angels.plural'),
                                'link'  => route_locale('angels.list')
                            ],
//                            [
//                                'title' => trans('front.main-menu.sub_menus.achieve.photo_donors'),
//                                'link'  => '#'
//                            ],
                        ]
                    ],
                    [
                        'title'    => trans('front.main-menu.sub_menus.achieve.shop'),
                        'children' => [
                            [
                                'title' => trans('front.main-menu.sub_menus.achieve.fonts'),
                                'link'  => route('fonts'),
                            ],
                            [
                                'title' => trans('front.main-menu.sub_menus.achieve.purchase_tracking'),
                                'link'  => route_locale('products.archive'),
                            ],
                        ],
                    ],
                    [
                        'title' => trans('front.main-menu.sub_menus.achieve.contact_us'),
                        'link'  => route_locale('contact')
                    ],
                ]
            ],
        ];
    }
}
