<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Posttype;
use App\Models\Printing;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class ManageServiceProvider extends ServiceProvider
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

	public static function sidebarSettingsMenu()
	{
		$array = [] ;

		/*-----------------------------------------------
		| Normal Options ...
		*/
		$array[] = ['account' , trans('settings.account.title') , 'sliders'] ;
		$array[] = ['settings' , trans('settings.downstream') , 'cog' , user()->isSuper()];
		$array[] = ['categories' , trans('posts.categories.meaning') , 'folder-o' , user()->isSuper()];

		/*-----------------------------------------------
		| Post Options ...
		*/
		$posttypes = Posttype::where('order' , '0')->orderBy('title')->get() ;
		foreach($posttypes as $posttype) {
			$array[] = [
				'posts/' . $posttype->slug ,
				$posttype->title ,
				$posttype->spreadMeta()->icon ,
				user()->isDeveloper()
			];
		}

		/*-----------------------------------------------
		| Developer Options ...
		*/
		$array[] = ['upstream' , trans('settings.upstream') , 'github-alt' , user()->isDeveloper()] ;

		return $array ;

	}

	public static function sidebarPostsMenu($folded = true)
	{
		$array = [] ;

		if($folded) {
			$groups = Posttype::groups()->get();
			foreach($groups as $group) {
				$posttypes = Posttype::where('order','>','0')->where('header_title' , $group->header_title)->orderBy('order')->orderBy('order')->get();
				$sub_menus = [] ;

				foreach($posttypes as $posttype) {
					if(user()->as('admin')->can("posts-".$posttype->slug)) {
						array_push($sub_menus , [
							'posts/' . $posttype->slug ,
							$posttype->title ,
							$posttype->spreadMeta()->icon ,
						]);
					}
				}

				array_push($array , [
					'icon' => "dot-circle-o",
					'caption' => $group->header_title? $group->header_title : trans('manage.global'),
					'link' => "asd",
					'sub_menus' => $sub_menus,
					'permission' => sizeof($sub_menus)? '' : 'dev',
				]);
			}
		}
		else {
			$posttypes = Posttype::where('order','>','0')->orderBy('order')->get();
			$sub_menus = [] ;

			foreach($posttypes as $posttype) {
				array_push($array , [
					'icon' => $posttype->spreadMeta()->icon,
					'caption' => $posttype->title,
					'link' => "posts/".$posttype->slug,
					'permission' => "posts-".$posttype->slug,
				]);
			}
		}

		return $array ;

	}

	public static function sidebarUsersMenu($folded = true)
	{
		$unfolded_menu = [] ;
		$folded_menu = [] ;

		/*-----------------------------------------------
		| Browsing the roles and making array for both folded and unfolded ways of display ...
		*/

		//foreach( Role::all() as $role) {
		//	if(user()->as('admin')->can('users-'.$role->slug)) {
		//		array_push($unfolded_menu , [
		//			'icon' => $role->menu_icon,
		//		     'caption' => $role->plural_title,
		//		     'link' => "users/browse/$role->slug",
		//		]);
		//		array_push($folded_menu, [
		//			"users/browse/$role->slug",
		//		     $role->plural_title ,
		//		     $role->menu_icon ,
		//		]);
		//	}
		//}

		/*-----------------------------------------------
		| Adding the "all users" button to both folded and unfolded arrays ...
		*/
		if(user()->as('admin')->can('users-all')) {
			array_push($unfolded_menu, [
				'icon'    => "address-book",
				'caption' => trans('people.commands.all_users'),
				'link'    => "users/browse/all",
			]);
			array_push($folded_menu, [
				"users/browse/all",
				trans('people.commands.all_users'),
				"address-book"
			]);
		}
		/*-----------------------------------------------
		| Conditionally returning the correct array ...
		*/

		if($folded) {
			return [[
				'icon' => "users",
			     'caption' => trans('people.site_users'),
			     'link' => "users",
			     'sub_menus' => $folded_menu,
			     'permission' => count($folded_menu)? 'any' : 'dev',
			]];
		}
		else {
			return $unfolded_menu;
		}

	}

	public static function topbarCreateMenu()
	{
		$posttypes = Posttype::all() ;
		$array = [] ;

		/*-----------------------------------------------
		| Post Types ...
		*/

		foreach($posttypes as $posttype) {
			if(user()->as('admin')->can("posts-$posttype->slug.create")) {
				$array[] = [
					"manage/posts/$posttype->slug/create/all" ,
				     trans("forms.button.create_in" , ['thing' => $posttype->title ,]),
				     $posttype->spreadMeta()->icon,
				];
			}
		}

		$array[] = ['-'] ;

		/*-----------------------------------------------
		| Donation Card ...
		*/
		if(user()->as('admin')->can('users-card-holder.create')) {
			$array[] = [
				"manage/cards/create" ,
			     trans("ehda.cards.create"),
			     'credit-card'
			] ;
		}

		/*-----------------------------------------------
		| Return ...
		*/
		return $array ;

	}


	public static function topbarNotificationMenu()
	{
		$posttypes = Posttype::all() ;
		$array = [] ;
		$total = 0 ;

		/*-----------------------------------------------
		| Post Types ...
		*/

		foreach($posttypes as $posttype) {
			if(user()->as('admin')->can("posts-$posttype->slug.publish")) {
				$count = Post::selector([
					'type' => $posttype->slug ,
				     'domain' => "auto" ,
				     'criteria' => "pending" ,
				])->count() ;
				$total += $count ;
				if($count) {
					$array[] = [
						"manage/posts/$posttype->slug/pending",
						$posttype->title . ' ' .trans("posts.criteria.pending") . pd(" ( $count )") ,
						$posttype->spreadMeta()->icon,
					];
				}
			}
		}

		$array[] = ['-'] ;

		/*-----------------------------------------------
		| Printings ...
		*/
		if(user()->as('admin')->can('users-card-holder.print')) {
			$count = Printing::selector([
				'criteria' => "pending" ,
			     'domain' => "auto" ,
			])->count() ;
			$total += $count ;

			if($count) {
				$array[] = [
					"manage/cards/printings" ,
					trans("ehda.printings.pending_cards") . pd(" ( $count )"),
				     'print' ,
				];
			}
		}

		/*-----------------------------------------------
		| Change Requests ...
		*/
		$count = User::selector([
			"status" => "changes_request",
		     'domain' => user()->domainsArray('edit') ,
		])->count();
		$total += $count ;

		if($count) {
			$array[] = [
				"manage/volunteers/browse/all/changes_request" ,
				trans("people.commands.changes_request") . pd(" ( $count )"),
				'copy' ,
			];
		}



		/*-----------------------------------------------
		| Return ...
		*/
		$array['total'] = $total ;
		return $array ;

	}

	public static function upstreamSettings()
	{
		return [
			['downstream', trans('settings.downstream')],
			['posttypes', trans('settings.posttypes')],
			['roles', trans('settings.roles')],
			['packages', trans('settings.packages')],
			['states', trans('settings.states')],
			['domains', trans('settings.domains')],
		     //['artisan' , trans("settings.artisan")]
		];
	}

}
