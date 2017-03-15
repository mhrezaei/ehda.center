<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\CategorySaveRequest;
use App\Http\Requests\Manage\FolderSaveRequest;
use App\Models\Category;
use App\Models\Folder;
use App\Models\Posttype;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CategoriesController extends Controller
{
	use ManageControllerTrait;

	public function __construct()
	{
		$this->Model         = new Folder();
		$this->browse_handle = 'counter';
		$this->view_folder   = "manage.categories";
	}

	public function update($model_id)
	{
		$model = Folder::find($model_id);
		$handle = $this->browse_handle ;

		//Run...
		if(!$model)
			return view('errors.m410');
		else
			return view('manage.settings.categories-row' , compact('model' , 'handle'));

	}


	public function index($request_type = null, $locale = null)
	{
		/*-----------------------------------------------
		| Posttypes ...
		*/
		$posttypes = Posttype::whereRaw("LOCATE('category' , `features`)")->orderBy('order')->paginate(100);
		Folder::updateDefaultFolders() ;

		/*-----------------------------------------------
		| type and locale ...
		*/
		if($request_type) {
			$type = Posttype::findBySlug($request_type);
			if(!$type) {
				return view('errors.410');
			}
			if(user()->as('admin')->cant("posts-$request_type.category")) {
				return view('errors.403');
			}
		}
		else {
			$type = new Posttype();
		}

		/*-----------------------------------------------
		| Locale and Page ...
		*/
		$page[0] = ['categories', trans('posts.categories.meaning')];
		if($type->exists) {
			$locale       = $type->normalizeRequestLocale($locale);
			$locale_title = trans("forms.lang.$locale");
			$page[1]      = ["type->slug/$locale", " $type->title ($locale_title)"];
		}

		/*-----------------------------------------------
		| View ...
		*/

		return view("manage.settings.categories-index", compact('page', 'posttypes', 'locale', 'type'));

	}

	public function createCategory($folder_id)
	{
		$folder = Folder::find($folder_id) ;
		if(!$folder) {
			return view('errors.410');
		}
		$type = $folder->posttype ;
		if($type->cannot('category')) {
			return view('errors.403');
		}


		$model = new Category();
		$model->folder_id = $folder_id ;
		
		return view("manage.settings.categories-edit",compact('model' , 'type'));
		
	}

	public function editCategory($id)
	{
		$model = Category::find($id) ;
		if(!$model) {
			return view('errors.410');
		}
		$type = $model->spreadMeta()->folder->posttype ;
		if($type->cannot('category')) {
			return view('errors.403');
		}

		return view("manage.settings.categories-edit",compact('model','type'));

	}

	public function saveCategory(CategorySaveRequest $request)
	{
		$data = $request->toArray() ;

		/*-----------------------------------------------
		| Delete Situation ...
		*/
		if($request->_submit == 'delete') {
			return $this->jsonAjaxSaveFeedback(Category::destroy($request->id), [
				'success_refresh' => 1,
			]);
		}

		/*-----------------------------------------------
		| Save Situation Validation ...
		*/
		if($request->id) {
			$model = Category::find($request->id);
			if(!$model) {
				return $this->jsonFeedback(trans('validation.http.Error410'));
			}
			$type = $model->folder->posttype;
		}
		else {
			$folder = Folder::find($request->folder_id);
			if(!$folder) {
				return $this->jsonFeedback(trans('validation.http.Error410'));
			}
			else {
				$type = $folder->posttype ;
			}
		}
		if($type->cannot('category')) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		$duplicate_query = Category::where('folder_id' , $request->folder_id)->where('id' , '!=' , $request->id) ;
		$duplicate_title = $duplicate_query->where('title' , $request->title)->first() ;
		$duplicate_slug = $duplicate_query->where('slug' , $request->slug)->first() ;


		if($duplicate_title) {
			return $this->jsonFeedback(trans('validation.unique' , [
				'attribute' => trans('validation.attributes.title'),
			]));
		}
		if($duplicate_slug) {
			return $this->jsonFeedback(trans('validation.unique' , [
				'attribute' => trans('validation.attributes.slug'),
			]));
		}

		/*-----------------------------------------------
		| Actual Save and its feedback...
		*/
		$success_callback = "rowUpdate('tblFolders','$request->folder_id')" ;
		if($request->_current_folder_id != $request->folder_id) {
			$success_callback .= ";rowUpdate('tblFolders','$request->_current_folder_id')" ;
		}

		return $this->jsonAjaxSaveFeedback(Category::store($request), [
			'success_callback' => $success_callback ,
		]);

	}

	public function createFolder($type_id, $locale)
	{
		$type = Posttype::find($type_id);
		if(!$type or !$type->has('category') or $type->cannot('category') or !in_array($locale, $type->locales_array)) {
			return view('errors.410');
		}

		$model              = new Folder();
		$model->posttype_id = $type->id;
		$model->locale      = $locale;

		return view("manage.settings.categories-folders-edit", compact('model', 'type'));

	}

	public function editFolder($folder_id)
	{
		$model = Folder::find($folder_id);
		if(!$model) {
			return view('errors.410');
		}
		$type = $model->spreadMeta()->posttype;
		if($type->cannot('category')) {
			return view('errors.403');
		}

		return view("manage.settings.categories-folders-edit", compact('model', 'type'));

	}

	public function saveFolder(FolderSaveRequest $request)
	{
		$data = $request->toArray() ;

		/*-----------------------------------------------
		| Delete Situation ...
		*/
		if($request->_submit == 'delete') {
			return $this->jsonAjaxSaveFeedback(Folder::safeDestroy($request->id), [
				'success_refresh' => 1,
			]);
		}

		/*-----------------------------------------------
		| Save Situation Validation ...
		*/
		if($request->id) {
			$model = Folder::find($request->id);
			if(!$model) {
				return $this->jsonFeedback(trans('validation.http.Error410'));
			}
			$type = $model->posttype;
			$unset_before_save = ['locale' , 'posttype_id'];
		}
		else {
			$type = Posttype::find($request->posttype_id);
			if(!$type) {
				return $this->jsonFeedback(trans('validation.http.Error410'));
			}
			if(!in_array($data['locale'], $type->locales_array)) {
				return $this->jsonFeedback(trans('validation.http.Error410'));
			}
			$unset_before_save = [];
		}
		if($type->cannot('category')) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		$duplicate_query = Folder::where('locale' , $data['locale'])->where('posttype_id' , $type->id)->where('id' , '!=' , $request->id) ;
		$duplicate_title = $duplicate_query->where('title' , $request->title)->first() ;
		$duplicate_slug = $duplicate_query->where('slug' , $request->slug)->first() ;


		if($duplicate_title) {
			return $this->jsonFeedback(trans('validation.unique' , [
				'attribute' => trans('validation.attributes.title'),
			]));
		}
		if($duplicate_slug) {
			return $this->jsonFeedback(trans('validation.unique' , [
				'attribute' => trans('validation.attributes.slug'),
			]));
		}

		/*-----------------------------------------------
		| Actual Save and its feedback...
		*/

		return $this->jsonAjaxSaveFeedback(Folder::store($request , $unset_before_save), [
			'success_callback' => "rowUpdate('tblFolders','$request->id')",
		]);

	}

}
