<?php

namespace App\Http\Controllers\Front;

use App\Models\Category;
use App\Models\Folder;
use App\Models\Post;
use App\Models\Receipt;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class TestController extends Controller
{
	public function postsConverter()
	{
		$posts = Post::all() ;
		foreach($posts as $post) {
			if($post->meta('text') or $post->meta('abstract')) {
				$post->text = $post->meta('text');
				$post->abstract = $post->meta('abstract');
				$post->starts_at = $post->meta('start_time') ;
				$post->ends_at = $post->meta('end_time') ;
				$post->updateMeta([
					'text' => false,
					'abstract' => false,
				]);
				$post->save() ;
			}
		}

		return "DONE :D" ;
	}

	public function index()
	{
		ss(User::defaultRole());
		ss(User::selector([
			'role' => ["customer"] ,
		])->count());
		ss(User::all()->count());
	}
}
