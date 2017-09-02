<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\CommentMassStatusRequest;
use App\Http\Requests\Manage\CommentProcessRequest;
use App\Http\Requests\Manage\CommentSaveRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Posttype;
use App\Providers\CommentServiceProvider;
use App\Providers\PostsServiceProvider;
use App\Providers\UploadServiceProvider;
use App\Traits\ManageControllerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vinkla\Hashids\Facades\Hashids;


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

	public function browse($request_tab = 'pending', $switch = null)
	{
		$_SESSION['debug_mode'] = 0;
		/*-----------------------------------------------
		| Break Switches ...
		*/
		$switches = array_normalize(array_maker($switch), [
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

		return view($this->view_folder . ".browse", compact('page', 'models', 'db', 'posttype' , 'switches' , 'switch'));


	}

	public function save(CommentSaveRequest $request)
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
		if(!$model->can('edit')) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		/*-----------------------------------------------
		| Save ...
		*/
		$ok = $model->saveStatus($request->status);
		if($ok) {
			$ok = Comment::store($request , ['status']);
		}

		/*-----------------------------------------------
		| Feedback ...
		*/

		return $this->jsonAjaxSaveFeedback($ok, [
			'success_callback' => "rowUpdate('tblComments','$request->id')",
		]);

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
	public function delete(Request $request)
	{
		$model = Comment::find($request->id);
		if(!$model) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		if(!$model->can('delete')) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		return $this->jsonAjaxSaveFeedback($model->delete(), [
			'success_callback' => "rowHide('tblComments' , '$request->id')",
			'success_refresh'  => false,
		]);

	}

	public function deleteMass(Request $request)
	{
		$ids = explode(',',$request->ids);
		$done = 0 ;
		foreach($ids as $id) {
			$model = Comment::find($id) ;
			if($model and $model->can('delete')) {
				$done += $model->delete() ;
			}
		}

		return $this->jsonAjaxSaveFeedback($done , [
			'success_refresh' => true ,
		     'success_message' => trans("forms.feed.mass_done", [
			     "count" => pd($done),
		     ]) ,
		]);

	}

	public function undelete(Request $request)
	{
		$model = Comment::onlyTrashed()->find($request->id);
		if(!$model) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		if(!$model->can('bin')) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		return $this->jsonAjaxSaveFeedback($model->undelete(), [
			'success_callback' => "rowHide('tblComments' , '$request->id')",
			'success_refresh'  => false,
		]);
	}

	public function undeleteMass(Request $request)
	{
		$ids = explode(',',$request->ids);
		$done = 0 ;
		foreach($ids as $id) {
			$model = Comment::onlyTrashed()->find($id) ;
			if($model and $model->can('bin')) {
				$done += $model->undelete() ;
			}
		}

		return $this->jsonAjaxSaveFeedback($done , [
			'success_refresh' => true ,
			'success_message' => trans("forms.feed.mass_done", [
				"count" => pd($done),
			]) ,
		]);

	}

	public function destroy(Request $request)
	{
		$model = Comment::onlyTrashed()->find($request->id);
		if(!$model) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		if(!$model->can('bin')) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		return $this->jsonAjaxSaveFeedback($model->forceDelete(), [
			'success_callback' => "rowHide('tblComments' , '$request->id')",
			'success_refresh'  => false,
		]);

	}

	public function destroyMass(Request $request)
	{
		$ids = explode(',',$request->ids);
		//$models = Comment::onlyTrashed()->whereIn('id' , $ids)
		$done = 0 ;
		foreach($ids as $id) {
			$model = Comment::onlyTrashed()->find($id) ;
			if($model and $model->can('bin')) {
				$done += $model->forceDelete() ;
			}
		}

		return $this->jsonAjaxSaveFeedback($done , [
			'success_refresh' => true ,
			'success_message' => trans("forms.feed.mass_done", [
				"count" => pd($done),
			]) ,
		]);

	}

	public function statusMass(CommentMassStatusRequest $request)
	{
		$ids = explode(',',$request->ids);
		//$models = Comment::onlyTrashed()->whereIn('id' , $ids)
		$done = 0 ;
		foreach($ids as $id) {
			$model = Comment::find($id) ;
			if($model and $model->can('process')) {
				$done += $model->saveStatus($request->status) ;
			}
		}

		return $this->jsonAjaxSaveFeedback($done , [
			'success_refresh' => true ,
			'success_message' => trans("forms.feed.mass_done", [
				"count" => pd($done),
			]) ,
		]);

	}

    public function convertToPost($comment)
    {
        $comment = CommentServiceProvider::smartFindComment($comment);

        if ($comment->exists) {
            $comment->spreadMeta();
            $post = $comment->post;
            if (
                $post->exists and
                $post->spreadMeta()->target_post_type and
                ($targetPosttype = Posttype::findBySlug($post->target_post_type)) and
                $targetPosttype->exists
            ) {
                $fields = CommentServiceProvider::translateFields($post->fields);

                if (
                    $post->conversions and
                    ($conversions = CommentServiceProvider::translateConversions($post->conversions)) and
                    is_array($conversions) and
                    count($conversions)
                ) {
                    foreach ($conversions as $from => $to) {
                        $comment->$to = $comment->$from;
                    }
                    $comment->__unset($from);

                    $fields[$to] = $fields[$from];
                    unset($fields[$from]);
                }

                $newPostData = [
                    'type'           => $post->target_post_type,
                    'source_comment' => $comment->id,
                    'sisterhood' => Hashids::encode(time()),
                    'is_draft' => 1,
                    'owned_by' => user()->id,
                    'created_by' => user()->id,
                    'locale' => $comment->locale,
                ];

                foreach ($comment->meta() as $key => $value) {
                    if (
                        ends_with($key, '_files') and
                        ($filesArray = json_decode($value, true)) and
                        is_array($filesArray)
                    ) {
                        if($key == 'image_files' and count($filesArray == 1)) {
                            $fileObj = UploadServiceProvider::smartFindFile($filesArray[0]);
                            if($fileObj->exists) {
                                $newPostData['featured_image'] = $fileObj->pathname;
                                unset($filesArray[0]);
                                $filesArray = array_values($filesArray);
                            }
                        }
                        if(count($filesArray)) {
                            if(!isset($newPostData['post_files'])) {
                                $newPostData['post_files'] = [];
                            }
                            foreach ($filesArray as $fileHashid) {
                                $newPostData['post_files'][] = [
                                    'src'   => $fileHashid,
                                    'label' => '',
                                    'link'  => '',
                                ];
                            }
                        }
                    }
                    unset($filesArray);
                }

                if($targetPosttype->has('domains')) {
                    $newPostData['domains'] = '|' . implode('|', getUsableDomains()) . '|';
                } else {
                    $newPostData['domains'] = '|global|';
                }

                foreach (array_keys($fields) as $fieldName) {
                    $newPostData[$fieldName] = $comment->$fieldName;
                }

                $id = Post::store($newPostData);
                $newPost = Post::findBySlug($id, 'id');
                return redirect("manage/posts/" . $newPost->type . "/edit/" . $newPost->hashid);
            }

            return $this->abort('404');
        }

        return $this->abort('403');
    }
}

