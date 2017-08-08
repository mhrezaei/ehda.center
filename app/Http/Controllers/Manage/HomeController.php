<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\SearchRequest;
use App\Providers\YasnaServiceProvider;
use App\Models\Posttype;
use App\Models\User;
use App\Traits\ManageControllerTrait;
use Psy\Util\Json;
use Illuminate\Support\Facades\View;


class HomeController extends Controller
{
	use ManageControllerTrait;
	private $page   = [];
	private $themes = ['orangered', 'pink', 'violet', 'green', 'primary', 'red', 'yellow'];


	public function __construct()
	{
		$this->page[0] = ['index', trans('manage.dashboard')];
	}

	public function index()
	{
		$page = $this->page;

		//return user()->as('volunteer-kashan')->pivot('permissions');
		//$digests = $this->index_digests();

		//$users = User::selector([
		//	'roleString' => ["volunteer-ardebil.8" , "volunteer-ardebil.3"] ,
		//]) ;
		//
		//return $users->count() ;
		return view('manage.home.index', compact('page', 'digests'));

	}

	private function index_digests()
	{
		$digests = [];

		/*-----------------------------------------------
		| Special Things ...
		*/
		$cards = User::selector([
			'role' => "card-holder" ,
		])->count() ;
		$digests[] = [
			'icon' => "credit-card" ,
		     'number' => $cards ,
		     'text' => trans("ehda.donation_card") ,
		     'theme' => "green" ,
		     'link' => user()->can("users-card-holder.browse")? url('/manage/cards') : '' ,
		] ;

		/*-----------------------------------------------
		| Post Types ...
		*/
		$types = Posttype::where('features', 'like', "%digest%")->get();

		foreach($types as $key => $type) {
			$posts     = $type->spreadMeta()->posts()->count();
			$digests[] = [
				'icon'   => $type->icon,
				'number' => $posts,
				'text'   => $type->title,
				'theme'  => $this->themes[ $key % sizeof($this->themes) ],
			     'link' => user()->can("posts-$type->slug.browse") ? url("/manage/posts/$type->slug") : "NO" ,
			];
		}

		return $digests;

	}


	public function widget($widget)
	{
		$ajax = true ;
		return view("manage.home.index-$widget" , compact('ajax'));
	}

	/**
	 * searches `users` for the exact match of `code_melli`, `card_no`, `email`, `mobile` and returns the first one only.
	 *
	 * @param SearchRequest $request
	 *
	 * @return Json
	 */
	public function searchPeople(SearchRequest $request)
	{
		/*-----------------------------------------------
		| Search ...
		*/
		$keyword = ed($request->keyword) ;
		$field = null ;

		if(str_contains($keyword , '@') and str_contains($keyword , '.')) { // <~~ Probably it's an email.
			$field = 'email' ;
		}
		elseif(str_contains($keyword , 'hashid')) {
			$keyword = trim(str_replace('hashid' , null , $keyword));
			if(is_numeric($keyword)) {
				return $this->jsonFeedback(hashid_encrypt($keyword, 'ids'));
			}
			else {
				return $this->jsonFeedback(hashid_decrypt0($keyword, 'ids'));
			}
		}
		elseif(str_contains($keyword , 'id')) {
			$field = 'id' ;
			$keyword = str_replace('id' , null , $keyword);
		}
		elseif($keyword=='taha') {
			return $this->jsonFeedback('جانم');
		}
		elseif(YasnaServiceProvider::isCodeMelli($keyword)) {
			$field = 'code_melli' ;
		}
		elseif(YasnaServiceProvider::isPhoneNumber($keyword)) {
			$field = 'mobile' ;
		}
		elseif( is_numeric($keyword)) {
			$field = 'card_no' ;
		}
		else {
			$field = 'id' ;
		}

		$query = User::where($field , $keyword);
		$total_found = $query->count() ;
		$model = $query->orderBy('id' , 'desc')->first();

		$success_message = View::make('manage.home.index-search-people-result' , compact('model' , 'total_found' , 'field' , 'keyword'))->render() ;


		/*-----------------------------------------------
		| Result ...
		*/
		return $this->jsonFeedback([
			'message' => $success_message ,
		     'feed_class' => " " ,
		]);

	}

}
