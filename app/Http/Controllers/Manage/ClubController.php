<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\DrawingRequest;
use App\Models\Drawing;
use App\Models\Post;
use App\Models\Receipt;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ClubController extends Controller
{
	use ManageControllerTrait;

	protected $page;
	protected $Model;
	protected $browse_counter;
	protected $browse_selector;
	protected $view_folder;

	public function drawPrepare(Request $request)
	{
		/*-----------------------------------------------
		| Row Selection ...
		*/
		$post = Post::find($request->id);
		if(!$post or $post->hasnot('event')) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		/*-----------------------------------------------
		| Action ...
		*/
		$limit = config('yasna.drawing_query_limit');

		$receipt_holders = Receipt::select('user_id')->where('operation_integer', 1)->limit($limit);

		$line_number = session()->get('line_number');;

		foreach($receipt_holders->get() as $receipt_holder) {
			$amount       = $post->receipts->where('user_id', $receipt_holder->user_id)->sum('purchased_amount');
			$total_points = intval(floor($amount / $post->meta('rate_point')));
			if($total_points) {
				Drawing::create([
					'user_id'    => $receipt_holder->user_id,
					'post_id'    => $post->id,
					'amount'     => $amount,
					'lower_line' => $line_number + 1,
					'upper_line' => $line_number += $total_points,
				]);
			}
		}

		$finished = boolval($line_number == session()->get('line_number'));
		$receipt_holders->update(['operation_integer' => '2',]);
		session()->put('line_number', $line_number);

		/*-----------------------------------------------
		| Feedback ...
		*/

		return $this->jsonAjaxSaveFeedback($finished, [
			'redirectTime'       => $finished ? 500 : 1,
			'success_message'    => trans('forms.feed.done'),
			'success_callback'   => "masterModal(url('manage/posts/act/$post->id/draw-winners' ))",
			'success_modalClose' => "0",

			'danger_message'  => trans('forms.feed.wait'),
			'danger_callback' => "drawingProgress($limit);rowUpdate('tblPosts','$request->post_id')",
		]);


	}

	public function drawSelect(DrawingRequest $request)
	{
		/*-----------------------------------------------
		| Post Selection ...
		*/
		$post = Post::find($request->post_id);
		if(!$post) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}
		$winners = $post->winners_array;


		/*-----------------------------------------------
		| User Selection ...
		*/
		$drawing_row = Drawing::pull($request->number);
		if(!$drawing_row or $drawing_row->post_id != $post->id) {
			return $this->jsonFeedback([
				'message'  => trans('forms.feed.error'),
				'callback' => "masterModal(url('manage/posts/act/$post->id/draw' ))",
			]);

		}
		$user = $drawing_row->user;
		if(!$user) {
			return $this->jsonFeedback(trans('people.form.user_deleted'));
		}
		if(in_array($user->id, $winners)) {
			return $this->jsonFeedback(trans('cart.user_already_won'));
		}

		/*-----------------------------------------------
		| Save ...
		*/
		$winners[] = $user->id;
		$ok        = $post->updateMeta(['winners' => $winners], true);

		/*-----------------------------------------------
		| Feedback ...
		*/
		return $this->jsonAjaxSaveFeedback($ok, [
				'success_message'    => $user->full_name,
				'success_modalClose' => false,
				'success_callback'   => "divReload( 'divWinnersTable' );rowUpdate('tblPosts','$request->post_id')",
			]
		);

	}

	public function drawDelete($key)
	{
		$post_id = session()->get('drawing_post');
		$post = Post::find($post_id);
		if($post) {
			$winners = $post->winners_array;
			unset($winners[$key]);
			$post->updateMeta(['winners' => array_values($winners)], true);
		}
	}

}
