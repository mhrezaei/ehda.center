<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\CommentProcessRequest;
use App\Models\Comment;
use App\Models\Posttype;
use App\Traits\ManageControllerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CommentsController extends Controller
{
	use ManageControllerTrait;

	protected $page;
	protected $Model;
	protected $browse_handle;
	protected $view_folder;

	public function __construct()
	{
		$this->Model = new Comment();

		$this->browse_handle = 'selector';
		$this->view_folder   = "manage.comments";
	}

	public function browse($request_tab = 'pending', $switches = null)
	{
		$_SESSION['debug_mode'] = 0;
		/*-----------------------------------------------
		| Break Switches ...
		*/
		$switches = array_normalize(array_maker($switches), [
			'post_id'      => "0",
			'type'         => "all",
			'replied_on'   => null,
			'email'        => "",
			'ip'           => "",
			'created_by'   => "",
			'published_by' => "",
			'search'       => "",
			'order_by'     => "created_at",
			'order_type'   => "desc",
			'criteria'     => $request_tab,
			'is_by_admin'  => "0",
		]);

		/*-----------------------------------------------
		| Posttype & Permission ...
		*/
		$posttype = Posttype::findBySlug($switches['type']);
		if($posttype->exists) {
			if(!Comment::checkManagePermission($switches['type'], $request_tab)) {
				return view('errors.403');
			}
		}
		else {
			$switches['type'] = Posttype::withPermit([
				'prefix'  => "comments-",
				'feature' => "comment",
				'permit'  => Comment::tab2permit($request_tab),
			]);
		}

		/*-----------------------------------------------
		| Page Browse ...
		*/
		$page = [
			'0' => ["comments", trans('posts.comments.users_comments'), "comments/"],
			'1' => [$request_tab, trans("posts.criteria.$request_tab"), "comments/$request_tab"],
		];

		/*-----------------------------------------------
		| Model ...
		*/
		$models = Comment::selector($switches)->orderBy($switches['order_by'], $switches['order_type'])->paginate(user()->preference('max_rows_per_page'));
		$db     = $this->Model;

		/*-----------------------------------------------
		| View ...
		*/

		return view($this->view_folder . ".browse", compact('page', 'models', 'db', 'posttype'));


	}

	public function process(CommentProcessRequest $request)
	{
		/*-----------------------------------------------
		| Model Selection ...
		*/
		$model = Comment::find($request->id);
		if(!$model) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		/*-----------------------------------------------
		| Permission ...
		*/
		if(!$model->can('process')) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		/*-----------------------------------------------
		| Save Status ...
		*/
		$ok = $model->saveStatus($request->status);

		/*-----------------------------------------------
		| Save Reply ...
		*/
		if($request->reply) {
			$ok = Comment::store([
				'user_id'      => user()->id,
				'post_id'      => $model->post_id,
				'type'         => $model->type,
				'replied_on'   => $model->id,
				'ip'           => request()->ip(),
				'text'         => $request->reply,
				'is_by_admin'  => "1",
				'published_at' => Carbon::now()->toDateTimeString(),
				'published_by' => user()->id,
			]);
		}

		/*-----------------------------------------------
		| Send Email if Requested ...
		*/
		if($request->reply and $request->send_email) {
			//@TODO: Send Email!
		}

		/*-----------------------------------------------
		| Feedback ...
		*/

		return $this->jsonAjaxSaveFeedback($ok, [
			'success_callback' => "rowUpdate('tblComments','$request->id')",
		]);


	}

}

