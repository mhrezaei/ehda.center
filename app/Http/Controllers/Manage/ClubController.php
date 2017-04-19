<?php

namespace App\Http\Controllers\Manage;

use App\Models\Drawing;
use App\Models\Post;
use App\Models\Receipt;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ClubController extends Controller
{
	use ManageControllerTrait ;

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
		$post = Post::find($request->id) ;
		if(!$post or $post->hasnot('event')) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		/*-----------------------------------------------
		| Action ...
		*/
		$limit = config('yasna.drawing_query_limit') ;

		$receipt_holders = Receipt::select('user_id')->where('operation_integer' , 1)->limit($limit)  ;

		$line_number = session()->get('line_number'); ;

		foreach($receipt_holders->get() as $receipt_holder) {
			$amount = $post->receipts->where('user_id' , $receipt_holder->user_id)->sum('purchased_amount') ;
			$total_points = intval($amount / $post->meta('rate_point')) ;
			Drawing::create([
				'user_id' => $receipt_holder->user_id,
				'post_id' => $post->id,
				'amount' => $amount,
				'lower_line' => $line_number + 1,
				'upper_line' => $line_number += $total_points,
			]);
		}

		$finished = boolval($line_number == session()->get('line_number')) ;
		$receipt_holders->update(['operation_integer' => '2',]);
		session()->put('line_number',$line_number);

		return $this->jsonAjaxSaveFeedback( $finished , [
			'redirectTime' => $finished? 500 : 1,
			'success_message' => trans('forms.feed.done') ,
			'success_callback' => "masterModal(url('manage/posts/act/$post->id/draw-winners' ))",
			'success_modalClose' => "0",

			'danger_message' => trans('forms.feed.wait') ,
			'danger_callback' => "drawingProgress($limit)",
		]);


	}

}
