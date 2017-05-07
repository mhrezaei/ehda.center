<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\PostSaveRequest;
use App\Models\Drawing;
use App\Models\Post;
use App\Models\Posttype;
use App\Models\Receipt;
use App\Models\User;
use App\Traits\ManageControllerTrait;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;


class PostsController extends Controller
{
	use ManageControllerTrait;

	protected $page;
	protected $Model;
	protected $browse_counter;
	protected $browse_selector;
	protected $view_folder;

	public function __construct()
	{
		$this->Model = new Post();
		$this->Model->setSelectorPara([ //@TODO: How to calculate?
		                                'locale' => "all",
		]);

		$this->browse_handle = 'selector';
		$this->view_folder   = "manage.posts";

	}

	public function tabUpdate($type, $request_tab = 'published', $switches = null)
	{
		//@TODO: tabUpdate
		//db, locale and posttype should be processed into a db instance and count the tab contents.
		//PROBLEM: the tab $refresh_url now works with the url took from the pagination method. How to send that url back to the page?
		//Now the tab refresh system is disabled, but commenting the $refresh_url from posts/tabs.blade

	}

	public function search($type, $locale, Request $request)
	{
		/*-----------------------------------------------
		| Check Permission ...
		*/
		if(!Post::checkManagePermission($type, 'search')) {
			return view('errors.403');
		}

		/*-----------------------------------------------
		| Revealing the Posttype...
		*/
		$posttype = Posttype::findBySlug($type);
		if(!$posttype) {
			return view('errors.404');
		}

		/*-----------------------------------------------
		| Locale ...
		*/
		if(!in_array($locale, $posttype->locales_array)) {
			$locale = 'all';
		}

		/*-----------------------------------------------
		| Page Browse ...
		*/
		$page = [
			'0' => ["posts/$posttype->slug", $posttype->title, "posts/$posttype->slug"],
			'1' => ["$locale/search", trans('forms.button.search_for') . " $request->keyword ", "posts/$type/$locale/"],
		];

		/*-----------------------------------------------
		| Only Panel ...
		*/
		if(strlen($request->keyword) < 3) {
			$db         = $this->Model;
			$page[1][1] = trans('forms.button.search');

			return view($this->view_folder . ".search", compact('page', 'models', 'db', 'locale', 'posttype'));
		}

		/*-----------------------------------------------
		| Model ...
		*/
		$selector_switches = [
			'type'     => $type,
			'locale'   => $locale,
			'criteria' => 'all',
			'search'   => $keyword = $request->keyword,
		];

		//if(in_array($request_tab, ['pending', 'bin']) and user()->as('admin')->cant("post-$type.publish")) {
		//	$selector_switches['owner'] = user()->id;
		//}

		$models = Post::selector($selector_switches)->orderBy('created_at', 'desc')->paginate(user()->preference('max_rows_per_page'));
		$db     = $this->Model;

		/*-----------------------------------------------
		| View ...
		*/

		return view($this->view_folder . ".browse", compact('page', 'models', 'db', 'locale', 'posttype', 'keyword'));


	}

	public function browse($type, $request_tab = 'published', $switches = null)
	{
		/*-----------------------------------------------
		| Check Permission ...
		*/
		if(!Post::checkManagePermission($type, $request_tab)) {
			return view('errors.403');
		}

		/*-----------------------------------------------
		| Reveal Posttype ...
		*/
		$posttype = Posttype::findBySlug($type);
		if(!$posttype->exists) {
			return view('errors.404');
		}

		/*-----------------------------------------------
		| Break Switches ...
		| (category, keyword, locale)
		*/
		$switches = array_normalize(array_maker($switches), [
			'locale'   => "all",
			'keyword'  => null,
			'category' => null,
			'id'       => "0",
		]);

		$locale = $switches['locale'];
		if(!in_array($locale, $posttype->locales_array)) {
			$locale = 'all';
		}


		/*-----------------------------------------------
		| Page Browse ...
		*/
		$page = [
			'0' => ["posts/$posttype->slug", $posttype->title, "posts/$posttype->slug"],
			'1' => ["$request_tab", trans("posts.criteria.$request_tab"), "posts/$posttype->slug/$request_tab"],
		];


		/*-----------------------------------------------
		| Model ...
		*/
		$selector_switches = [
			'type'     => $type,
			'locale'   => $locale,
			'criteria' => $request_tab,
			'id'       => $switches['id'],
		];

		if(in_array($request_tab, ['pending', 'bin']) and user()->as('admin')->cant("post-$type.publish")) {
			$selector_switches['owner'] = user()->id;
		}

		$models = Post::selector($selector_switches)->orderBy('created_at', 'desc')->paginate(user()->preference('max_rows_per_page'));
		//		$models->appends(['sort' => 'votes'])->links() ;
		$db = $this->Model;

		/*-----------------------------------------------
		| View ...
		*/

		return view($this->view_folder . ".browse", compact('page', 'models', 'db', 'locale', 'posttype'));

	}

	public function editor($post_type, $post_id)
	{
		//Model...
		$model = Post::find($post_id);
		if(!$model or $model->type != $post_type or !$model->posttype->exists) {
			return view('errors.410');
		}
		if(!$model->spreadMeta()->canEdit()) {
			return view('errors.403');
		}

		//Page...
		$page = [
			'0' => ["posts/$post_type", $model->posttype->title, "posts/$post_type"],
			'1' => ["posts/$post_type/edit/$post_id", trans('forms.button.edit'), "posts/$post_type/edit/$post_id"],
		];

		//View...
		return view("manage.posts.editor", compact('page', 'model'));


	}

	public function create($type_slug, $locale = null, $sisterhood = null)
	{
		//Permission...
		if(user()->as('admin')->cannot("post-$type_slug.create")) {
			return view('errors.403');
		}

		//Model...
		$model       = new Post();
		$model->type = $type_slug;

		if($model->has('locales')) {
			if(!$locale or $locale == 'all') {
				$model->locale = 'fa';
			}
			elseif(!in_array($locale, $model->posttype->locales_array)) {
				return view('errors.410');
			}
			else {
				$model->locale = $locale;
			}

		}
		else {
			$model->locale = 'fa';
		}

		if($sisterhood) {
			$model->sisterhood = $sisterhood;
		}
		else {
			$model->sisterhood = Hashids::encode(time());
		}


		$model->template = $model->posttype->spreadMeta()->template;
		if(!$model->posttype->exists) {
			return view('errors.410');
		}

		//Page...
		$page = [
			'0' => ["posts/$type_slug", $model->posttype->title, "posts/$type_slug"],
			'1' => ["posts/$type_slug/create", trans('forms.button.add'), "posts/$type_slug/create"],
		];

		//View...
		return view("manage.posts.editor", compact('page', 'model'));


	}

	public function checkSlug($post_id, $post_type, $post_locale, $suggested_slug = null)
	{
		$suggested_slug = trim($suggested_slug);
		if($suggested_slug) {
			$approved_slug = Post::normalizeSlug($post_id, $post_type, $post_locale, $suggested_slug);
		}
		else {
			$approved_slug = '';
		}

		return view("manage.posts.editor-slug-feedback", compact('suggested_slug', 'approved_slug'));

	}


	public function save(PostSaveRequest $request)
	{
		$data = $request->toArray();

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
			$model         = new Post();
			$model->type   = $request->type;
			$model->locale = $data['locale'];
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


		$command = $data['_submit'];
		$allowed = true;
		if(in_array($command, ['delete', 'delete_original'])) {
			return $this->saveDelete($model, $request);
			// this (^) is to completely bypass delete commands. Security will be checked over there.
		}
		if(in_array($command, ['publish'])) {
			$allowed = ($allowed and $model->canPublish());
		}
		if(in_array($command, ['unpublish'])) {
			if($model->canPublish()) {
				return $this->saveUnpublish($model);
			}
			else {
				return $this->jsonFeedback(trans('validation.http.Error503'));
			}

		}
		if($request->id) {
			$allowed = ($allowed and $model->canEdit());
		}
		else {
			$allowed = ($allowed and $model->can('create'));
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
			$data['owned_by'] = user()->id;
		}

		/*-----------------------------------------------
		| Slug ...
		*/
		if($model->has('slug')) {
			$model->slug  = $data['slug'];
			$data['slug'] = $model->normalized_slug;
		}
		else {
			$data['slug'] = null;
		}


		/*-----------------------------------------------
		| Long Title ...
		*/
		if($model->has('long_title')) {
			$data['long_title'] = $data['title'];
			$data['title']      = str_limit($data['title'], 100);
		}

		/*-----------------------------------------------
		| Published_at ...
		*/

		if($model->has('schedule') and $data['_schedule']) {
			$data['published_at'] = makeDateTimeString($data['publish_date'], $data['publish_hour'], $data['publish_minute']);
		}
		unset($data['publish_date']);
		unset($data['publish_hour']);
		unset($data['publish_minute']);

		/*-----------------------------------------------
		| sale_expires_at ...
		*/

		if($model->has('price') and $data['sale_expires_date']) {
			if(!$data['sale_expires_hour']) {
				$data['sale_expires_hour'] = '00';
			}
			if(!$data['sale_expires_minute']) {
				$data['sale_expires_minute'] = '00';
			}
			$data['sale_expires_at'] = makeDateTimeString($data['sale_expires_date'], $data['sale_expires_hour'], $data['sale_expires_minute']);
		}
		unset($data['sale_expires_date']);
		unset($data['sale_expires_hour']);
		unset($data['sale_expires_minute']);

		/*-----------------------------------------------
		| Language ...
		*/
		if(!in_array($data['locale'], $model->posttype->locales_array)) {
			return $this->jsonFeedback();

		}

		/*-----------------------------------------------
		| Album ...
		*/
		if($model->has('album')) {
			$data['post_photos'] = Post::savePhotos($data);
		}


		/*
		|--------------------------------------------------------------------------
		| Save
		|--------------------------------------------------------------------------
		| Buttons: publish, approval (send for editor), save (draft)
		| Possible moods: ($model->editor_mood) new: creating new post , original: editing an original post , copy: editing a copy post
		*/
		$model_delete_after_save = false;

		/*-----------------------------------------------
		| In case of Publish command ...
		*/
		if($command == 'publish') {
			$data['is_draft']      = false;
			$data['moderate_note'] = null;

			switch ($model->editor_mood) {
				case 'new':
					$data['published_by'] = user()->id;
					$data['moderated_By'] = user()->id;
					$data['moderated_at'] = Carbon::now()->toDateTimeString();
					if(!isset($data['published_at'])) {
						$data['published_at'] = Carbon::now()->toDateTimeString();
					}
					$redirect_url = url("manage/posts/" . $data['type'] . "/edit/-ID-");
					break;

				case 'original':
					if(!$model->isApproved()) {
						$data['published_by'] = user()->id;
						$data['moderated_By'] = user()->id;
						$data['published_at'] = Carbon::now()->toDateTimeString();
						$data['moderated_at'] = Carbon::now()->toDateTimeString();
					}
					$redirect_url = false;
					break;

				case 'copy':
					$original                = $model->original(); //works fine even if original record doesn't exist anymore.
					$model_delete_after_save = true;
					$data['id']              = $original->id;
					$data['moderated_By']    = user()->id;
					$data['moderated_at']    = Carbon::now()->toDateTimeString();
					$redirect_url            = url("manage/posts/" . $data['type'] . "/edit/" . $original->id);
					break;

				default:
					return $this->jsonFeedback();

			}
		}

		/*-----------------------------------------------
		| In case of Approval command ...
		*/
		elseif($command == 'approval') {
			$data['is_draft']      = false;
			$data['moderate_note'] = null;

			switch ($model->editor_mood) {
				case 'new':
					$redirect_url = url("manage/posts/" . $data['type'] . "/edit/-ID-");
					break;

				case 'original':
					if($model->isApproved()) {
						$data['copy_of'] = $model->id;
						$data['id']      = 0;
						$redirect_url    = url("manage/posts/" . $data['type'] . "/edit/-ID-");
					}
					else {
						$redirect_url = false;
					}
					break;

				case 'copy':
					$redirect_url = false;
					break;

				default:
					return $this->jsonFeedback();
			}

		}

		/*-----------------------------------------------
		| In case of Save command ...
		*/
		elseif($command == 'save') {
			$data['is_draft']      = true;
			$data['moderate_note'] = null;

			switch ($model->editor_mood) {
				case 'new':
					$redirect_url = url("manage/posts/" . $data['type'] . "/edit/-ID-");
					break;

				case 'original':
					if($model->isApproved()) {
						$data['copy_of']  = $model->id;
						$data['id']       = 0;
						$data['owned_by'] = user()->id;
						$redirect_url     = url("manage/posts/" . $data['type'] . "/edit/-ID-");
					}
					else {
						$redirect_url = false;
					}
					break;

				case 'copy':
					$redirect_url = false;
					break;

				default:
					return $this->jsonFeedback();
			}

		}

		/*-----------------------------------------------
		| In case of Reject command ...
		*/
		elseif($command == 'reject') {
			$data['is_draft']     = true;
			$data['moderated_by'] = user()->id;
			$data['moderated_at'] = Carbon::now()->toDateTimeString();

			switch ($model->editor_mood) {
				case 'new':
					//this is a never-happening situation!
					return $this->jsonFeedback();
					break;

				case 'original':
					if($model->isApproved()) {
						//this is a never-happening situation!
						return $this->jsonFeedback();
					}
					else {
						$redirect_url = url("manage/posts/" . $data['type'] . "/pending");
					}
					break;

				case 'copy':
					$redirect_url = url("manage/posts/" . $data['type'] . "/pending");
					break;

				default:
					return $this->jsonFeedback();
			}

		}

		/*-----------------------------------------------
		| Safety ...
		*/
		else {
			return $this->jsonFeedback();
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
			$saved_model = Post::find($saved);

			/*-----------------------------------------------
			| Delete Copies ...
			*/
			if($model_delete_after_save) {
				$model->forceDelete();
			}

			/*-----------------------------------------------
			| Categories ...
			*/
			if($model->has('category')) {
				$saved_model->saveCategories($data);
			}

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
		if($redirect_url) {
			$redirect_url = str_replace('-ID-', $saved, $redirect_url);
			$refresh_page = false;
		}
		else {
			$refresh_page = false; //true ;
		}

		return $this->jsonAjaxSaveFeedback($saved, [
			'success_redirect' => $redirect_url,
			'success_refresh'  => $refresh_page,
		     'success_callback' => "divReload('divPublishPanel')" ,
		]);

	}

	public function saveUnpublish($model)
	{
		return $this->jsonAjaxSaveFeedback($model->unpublish(), [
			'success_callback' => "divReload('divPublishPanel')" ,
		]);
	}


	public function saveDelete($model, $request)
	{
		$command = $request->toArray()['_submit'];
		$done    = false;

		/*-----------------------------------------------
		| Action ...
		*/
		if($command == 'delete') {
			if($model->canDelete()) {
				$done = $model->delete();
			}
			else {
				return $this->jsonFeedback(trans('validation.http.Error503'));
			}
		}
		elseif($command == 'delete_original') {
			$original_model = $model->original();
			if(!$original_model or !$original_model->exists) {
				return $this->jsonFeedback(trans('validation.http.Error410'));
			}
			elseif(!$original_model->canDelete()) {
				return $this->jsonFeedback(trans('validation.http.Error503'));
			}
			else {
				$done = $original_model->delete();
				$done = $original_model->copies()->delete();
			}
		}

		/*-----------------------------------------------
		| Feedback ...
		*/

		return $this->jsonAjaxSaveFeedback($done, [
			'success_redirect' => url("manage/posts/$model->type"),
		]);

	}

	public function delete(Request $request)
	{
		$model = Post::find($request->id);
		if(!$model) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		if(!$model->canDelete()) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		return $this->jsonAjaxSaveFeedback($model->delete(), [
			'success_callback' => "rowHide('tblPosts' , '$request->id')",
			'success_refresh'  => false,
		]);

	}

	public function deleteMass(Request $request)
	{
		$ids  = explode(',', $request->ids);
		$done = 0;
		foreach($ids as $id) {
			$model = Post::find($id);
			if($model and $model->canDelete()) {
				$done += $model->delete();
			}
		}

		return $this->jsonAjaxSaveFeedback($done, [
			'success_refresh' => true,
			'success_message' => trans("forms.feed.mass_done", [
				"count" => pd($done),
			]),
		]);

	}


	public function undelete(Request $request)
	{
		$model = Post::onlyTrashed()->find($request->id);
		if(!$model) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		if(!$model->canDelete()) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		return $this->jsonAjaxSaveFeedback($model->undelete(), [
			'success_callback' => "rowHide('tblPosts' , '$request->id')",
			'success_refresh'  => false,
		]);

	}

	public function undeleteMass(Request $request)
	{
		$ids  = explode(',', $request->ids);
		$done = 0;
		foreach($ids as $id) {
			$model = Post::onlyTrashed()->find($id);
			if($model and $model->canDelete()) {
				$done += $model->undelete();
			}
		}

		return $this->jsonAjaxSaveFeedback($done, [
			'success_refresh' => true,
			'success_message' => trans("forms.feed.mass_done", [
				"count" => pd($done),
			]),
		]);

	}

	public function destroy(Request $request)
	{
		$model = Post::onlyTrashed()->find($request->id);
		if(!$model) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		if(!$model->canDelete()) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		return $this->jsonAjaxSaveFeedback($model->forceDelete(), [
			'success_callback' => "rowHide('tblPosts' , '$request->id')",
			'success_refresh'  => false,
		]);

	}

	public function destroyMass(Request $request)
	{
		$ids = explode(',', $request->ids);
		//$models = Comment::onlyTrashed()->whereIn('id' , $ids)
		$done = 0;
		foreach($ids as $id) {
			$model = Post::onlyTrashed()->find($id);
			if($model and $model->canDelete()) {
				$done += $model->forceDelete();
			}
		}

		return $this->jsonAjaxSaveFeedback($done, [
			'success_refresh' => true,
			'success_message' => trans("forms.feed.mass_done", [
				"count" => pd($done),
			]),
		]);

	}

	public function changeOwner(Request $request)
	{
		/*-----------------------------------------------
		| Post Selection ...
		*/
		$post = Post::find($request->id) ;
		if(!$post or !$post->exists) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}
		if(!$post->canPublish()) {
			return $this->jsonFeedback(trans('validation.http.Error503'));
		}

		/*-----------------------------------------------
		| User Selection ...
		*/
		$user = User::find($request->owner_id) ;
		if(!$user or !$user->exists or $user->is_not_an('admin')) {
			return $this->jsonFeedback(trans('people.form.user_deleted'));
		}

		/*-----------------------------------------------
		| Save...
		*/
		if($post->owned_by == $user->id) {
			$ok = 1 ;
		}
		else {
			$ok = Post::store([
				'id' => $post->id ,
			     'owned_by' => $user->id ,
			]);
		}

		/*-----------------------------------------------
		| Feedback ...
		*/
		return $this->jsonAjaxSaveFeedback( $ok , [
				'success_callback' => "rowUpdate('tblPosts' , '$request->id')",
		]);




	}

	public function makeClone(Request $request)
	{
		$data = $request->toArray();

		/*-----------------------------------------------
		| Model Retrieving and Security Check ...
		*/
		$model = Post::withTrashed()->find($request->id);
		if(!$model) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		if(!$model->can('create')) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		/*-----------------------------------------------
		| Validation ...
		*/
		if($model->has('locales')) {
			if(!in_array($data['locale'], $model->posttype->locales_array)) {
				return $this->jsonFeedback();
			}
			if($data['is_sister']) {
				$sister = $model->in($data['locale']);
				if($sister->exists) {
					return $this->jsonFeedback(trans('posts.form.translation_already_made'));
				}
			}
		}
		else {
			//(to be safe)
			$data['is_sister'] = 0;
			$data['locale']    = $model->locale;
		}

		/*-----------------------------------------------
		| Save ...
		*/
		$new_model = new Post();
		$new_model = $model->replicate();
		if($model->has('locales')) {
			$new_model->locale = $data['locale'];
		}
		if(!$data['is_sister']) {
			$new_model->sisterhood = Hashids::encode(time());
		}
		$new_model->slug         = $new_model->normalized_slug;
		$new_model->is_draft     = 1;
		$new_model->copy_of      = 0;
		$new_model->published_at = null;
		$new_model->moderated_at = null;
		$new_model->created_by   = 0;
		$new_model->published_by = 0;
		$new_model->moderated_by = 0;
		$new_model->owned_by     = user()->id;
		$new_model->created_by   = user()->id;

		$new_model->save();


		/*-----------------------------------------------
		| Feedback ...
		*/

		return $this->jsonAjaxSaveFeedback($new_model->id > 0, [
			'success_callback' => "rowUpdate('tblPosts','$request->id')",
			'success_redirect' => $data['_submit'] == 'redirect_after_save' ? $new_model->edit_link : '',
		]);


	}


}
