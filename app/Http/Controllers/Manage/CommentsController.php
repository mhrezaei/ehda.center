<?php

namespace App\Http\Controllers\Manage;

use App\Models\Comment;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CommentsController extends Controller
{
	use ManageControllerTrait ;

	protected $page ;
	protected $Model ;
	protected $browse_counter ;
	protected $browse_selector ;
	protected $view_folder ;

	public function __construct()
	{
		$this->Model = new Comment() ;

		$this->browse_hanle = 'selector' ;
		$this->view_folder = "manage.comments" ;
	}

}

