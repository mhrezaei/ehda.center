<?php

namespace App\Http\Controllers\Manage;

use App\Models\Post;
use App\Models\Posttype;
use App\Traits\ManageControllerTrait;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{
	use ManageControllerTrait ;

	protected $page ;
	protected $Model ;
	protected $browse_counter ;
	protected $browse_selector ;
	protected $view_folder ;

	public function __construct()
	{
		$this->Model = new Post() ;
		$this->Model->setSelectorPara([ //@TODO: How to calculate?
				'locale' => "all",
		]);

		$this->browse_handle = 'selector' ;
		$this->view_folder = "manage.posts" ;

	}

	public function browse($posttype ,$request_tab = 'published' , $switches=null)
	{
		//Check Permission...
		if(!Post::checkManagePermission($posttype,$request_tab))
			return view('errors.403');

		//Reveal posttype...
		$posttype = Posttype::findBySlug($posttype);
		if(!$posttype)
			return view('errors.404');

		//Process Switches...
		$locale = null ;
		//@TODO: category, keywords and lang are to be processed here!

		//Page Browse...
		$page = [
			'0' => ["posts/$posttype->slug" , $posttype->title , "posts/$posttype->slug" ],
			'1' => ["$request_tab" , trans("posts.criteria.$request_tab") , "posts/$posttype->slug/$request_tab"],
		];

		//Category... //@TODO

		//Model...
		$db = $this->Model ;
		$models = Post::selector([
			'posttype' => $posttype->slug,
			'locale' => $locale,
			'criteria' => $request_tab,
		])->orderBy('created_at' , 'desc')->paginate(user()->preference('max_rows_per_page'));;


		//View...
		return view($this->view_folder.".browse",compact('page' , 'models' , 'db'  , 'locale' , 'posttype'));

	}

	public function create($type_slug, $locale = null)
	{
		//Permission...
		if(user()->as('admin')->cannot("post-$type_slug.create"))
			return view('errors.403');

		//Model...
		$model = new Post() ;
		$model->type = $type_slug ;

		if($model->has('locales')) {
			if(!$locale)
				$model->locale = 'fa' ;
			elseif(!in_array($locale , $model->posttype->locales_array ))
				return view('errors.410');
			else
				$model->locale = $locale ;
		}

		$model->template = $model->posttype->spreadMeta()->template ;
		if(!$model->posttype->exists)
			return view('errors.410');

		//Page...
		$page = [
				'0' => ["posts/$type_slug" , $model->posttype->title , "posts/$type_slug" ],
				'1' => ["posts/$type_slug/create" , trans('forms.button.add') , "posts/$type_slug/create"],
		];

		//View...
		return view("manage.posts.editor",compact('page' , 'model'));



	}


}
