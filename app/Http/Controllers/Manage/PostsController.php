<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\PostSaveRequest;
use App\Models\Post;
use App\Models\Posttype;
use App\Traits\ManageControllerTrait;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

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
		else
			$model->locale = 'fa' ;

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

	public function save(PostSaveRequest $request)
	{
		$data = $request->toArray() ;

		/*
		|--------------------------------------------------------------------------
		| Model Reveals
		|--------------------------------------------------------------------------
		|
		*/

		if($request->id) {
			$model = Post::find($request->id);
			if(!$model) {
				return $this->jsonFeedback(trans('validation.http.Error410'));
			}
		}
		else {
			$model = new Post() ;
			$model->type = $request->type ;
			if(!$model->posttype()) {
				return $this->jsonFeedback(trans('validation.http.Error410'));
			}
		}

		/*
		|--------------------------------------------------------------------------
		| Security and Access Control
		|--------------------------------------------------------------------------
		|
		*/


		$command = $data['_submit'] ;
		$allowed = true ;
		if(in_array($command , ['delete' , 'delete_origina'])) {
			return $this->saveDelete($request) ;
			// this (^) is to completely bypass delete commands. Security will be checked over there.
		}
		if(in_array($command , ['publish' , 'unpublish'])) {
			$allowed = ($allowed and $model->canPublish()) ;
		}
		if($request->id) {
			$allowed = ($allowed and $model->canEdit()) ;
		}
		else {
			$allowed = ($allowed and $model->can('create')) ;
		}
		if(!$allowed) {
			return $this->jsonFeedback(trans('validation.http.Error403'));

		}

		/*
		|--------------------------------------------------------------------------
		| Data Purification
		|--------------------------------------------------------------------------
		| owned_by , long_title , meta , albums , categories , published_at , sale_expires_at
		*/

		/*-----------------------------------------------
		| owned_by ...
		*/
		if(!$model->exists) {
			$data['owned_by'] = user()->id ;
		}


		/*-----------------------------------------------
		| Long Title ...
		*/

		if($model->has('long_title')) {
			$data['long_title'] = $data['title'] ;
			$data['title'] = str_limit($data['title'] , 100);
		}

		/*-----------------------------------------------
		| Published_at ...
		*/

		if($model->has('schedule') and $data['_schedule']) {
			$data['published_at'] = makeDateTimeString($data['publish_date'] , $data['publish_hour'] , $data['publish_minute']);
		}
		unset($data['publish_date']);
		unset($data['publish_hour']);
		unset($data['publish_minute']);

		/*-----------------------------------------------
		| sale_expires_at ...
		*/

		if($model->has('price') and $data['sale_expires_date']) {
			if(!$data['sale_expires_hour']) $data['sale_expires_hour'] = '00' ;
			if(!$data['sale_expires_minute']) $data['sale_expires_minute'] = '00' ;
			$data['sale_expires_at'] = makeDateTimeString($data['sale_expires_date'] , $data['sale_expires_hour'] , $data['sale_expires_minute']);
			return $this->jsonFeedback($data['sale_expires_at']);

		}
		unset($data['sale_expires_date']);
		unset($data['sale_expires_hour']);
		unset($data['sale_expires_minute']);

		/*
		|--------------------------------------------------------------------------
		| Save
		|--------------------------------------------------------------------------
		| Buttons: publish, approval (send for editor), save (draft)
		| Possible moods: ($model->editor_mood) new: creating new post , original: editing an original post , copy: editing a copy post
		*/
		$model_delete_after_save = false ;

		/*-----------------------------------------------
		| In case of Publish command ...
		*/
		if($command == 'publish') {
			$data['is_draft'] = false ;
			$data['moderate_note'] = null ;

			switch($model->editor_mood) {
				case 'new':
					$data['published_by'] = user()->id ;
					$data['moderated_By'] = user()->id ;
					$data['published_at'] = Carbon::now()->toDateTimeString() ;
					$data['moderated_at'] = Carbon::now()->toDateTimeString() ;
					break;

				case 'original':
					break;

				case 'copy':
					$original = $model->original() ; //works fine even if original record doesn't exist anymore.
					$model_delete_after_save = true ;
					$data['id'] = $original->id ;
					$data['moderated_By'] = user()->id ;
					$data['moderated_at'] = Carbon::now()->toDateTimeString() ;
					break;
			}
		}

		/*-----------------------------------------------
		| In case of Approval command ...
		*/
		if($command == 'approval') {
			$data['is_draft'] = false ;
			$data['moderate_note'] = null ;

			switch($model->editor_mood) {
				case 'new':
					break;

				case 'original':
					if($model->isApproved()) {
						$data['copy_of'] = $model->id ;
						$data['id'] = 0 ;
					}
					break;

				case 'copy':
					break;
			}

		}

		/*-----------------------------------------------
		| In case of Save command ...
		*/
		if($command == 'save') {
			$data['is_draft'] = true ;
			$data['moderate_note'] = null ;

			switch($model->editor_mood) {
				case 'new':
					break;

				case 'original':
					if($model->isApproved()) {
						$data['copy_of'] = $model->id ;
						$data['id'] = 0 ;
					}
					break;

				case 'copy':
					break;
			}

		}

		/*-----------------------------------------------
		| In case of Reject command ...
		*/
		if($command == 'reject') {
			$data['is_draft'] = true ;
			$data['moderated_by'] = user()->id ;
			$data['moderated_at'] = Carbon::now()->toDateTimeString() ;

			switch($model->editor_mood) {
				case 'new':
					//this is a never-happening situation!
					return $this->jsonFeedback();
					break;

				case 'original':
					if($model->isApproved()) {
						//this is a never-happening situation!
						return $this->jsonFeedback();
					}
					break;

				case 'copy':
					break;
			}

		}

		/*-----------------------------------------------
		| Actual Save ...
		*/
		$saved = Post::store($data);


		/*
		|--------------------------------------------------------------------------
		| Post Save Actions
		|--------------------------------------------------------------------------
		| categories, delete copies
		*/

		if($saved) {

			/*-----------------------------------------------
			| Delete Copies ...
			*/
			if($model_delete_after_save) {
				$model->forceDelete();
			}

			/*-----------------------------------------------
			| Categories ...
			*/
			//@TODO

			/*-----------------------------------------------
			| Post History ...
			*/
			//@TODO



		}




		/*
		|--------------------------------------------------------------------------
		| Feedback and Redirect
		|--------------------------------------------------------------------------
		|
		*/
		if($model->exists) {
			$redirect_url = null ;
			$refresh_page = true ;
		}
		else {
			$redirect_url = url("manage/posts/".$data['type']."/edit/".$data['id']);
			$refresh_page = false ;
		}

		return $this->jsonAjaxSaveFeedback($saved , [
			'success_redirect' => $redirect_url,
			'success_refresh' => $refresh_page,
		]);

	}

	public function saveDelete($request)
	{

	}


}
