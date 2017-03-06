<?php

namespace App\Providers;

use App\Models\Posttype;
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

	public static function sidebarPostsMenu($folded = false)
	{
		$array = [] ;

		if($folded) {
			$groups = Posttype::groups()->get();
			foreach($groups as $group) {
				$posttypes = Posttype::where('header_title' , $group->header_title)->orderBy('order')->orderBy('order')->get();
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
					'caption' => $group->header_title? $group->header_title : trans('posts.manage.global'),
					'link' => "asd",
					'sub_menus' => $sub_menus,
					'permission' => sizeof($sub_menus)? '' : 'dev',
				]);
			}
		}
		else {
			$posttypes = Posttype::orderBy('order')->get();
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
}
