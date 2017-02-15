<?php
namespace App\Traits;


trait ManageControllerTrait
{

	/*
	|--------------------------------------------------------------------------
	| Response Methods
	|--------------------------------------------------------------------------
	|
	*/


	/**
	 * responses to the update-row request from the grid views and depends on the environment variables set in the main controller constructor
	 * @param $model_id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function update($model_id)
	{
		//Preparations...
		$Model = $this->Model ;
		$model = $Model::withTrashed()->find($model_id);
		$counter = $this->browse_counter ;
		$selector = $this->browse_selector ;

		//Run...
		if(!$model)
			return view('errors.m410');
		else
			return view($this->view_folder.'.browse-row' , compact('model' , 'counter' , 'selector'));

	}

	public function modalActions($model_id , $view_file)
	{
		return 12 ;
	}
	/*
	|--------------------------------------------------------------------------
	| Simple Return
	|--------------------------------------------------------------------------
	| Just for the ease of access
	*/
	private function feedback($is_ok = false , $message = null)
	{
		if(!$is_ok) {
			if(!$message)
				$message = trans('forms.feed.error') ;
			echo ' <div class="alert alert-danger">'. $message .'</div> ';
			die() ;
		}
		else {
			if(!$message)
				$message = trans('forms.feed.done') ;
			echo ' <div class="alert alert-success">'. $message .'</div> ';
			die() ;

		}
	}

	/*
	|--------------------------------------------------------------------------
	| Shortcuts to Json Feeds
	|--------------------------------------------------------------------------
	|
	*/

	private function jsonFeedback($message=null , $setting = [])
	{
		//Preferences...
		if(!$message) $message = trans('validation.invalid') ;
		if(is_array($message) and !sizeof($setting))
			$setting = $message ;

		$default = [
			'ok' => 0 ,
			'message' => $message ,
			'redirect' => '' ,
			'callback' => '' ,
			'refresh' => 0 ,
			'modalClose' => 0 ,
			'updater' => '' ,
			'redirectTime' => 1000,
		];

		foreach($default as $item => $value) {
			if(!isset($setting[$item]))
				$setting[$item] = $value ;
		}

		//Normalization...
		if($setting['redirect'])
			$setting['redirect'] = url($setting['redirect']);

		//Action...
		return json_encode($setting) ;

	}
	private function jsonSaveFeedback($is_saved , $setting=[])
	{
		//Preferences...
		$default = [
			'success_message' => trans('forms.feed.done') ,
			'success_redirect' => '' ,
			'success_callback' => '' ,
			'success_refresh' => '0' ,
			'success_modalClose' => '0' ,
			'success_updater' => '' ,
			'redirectTime' => 1000,

			'danger_message' => trans('validation.invalid'),
			'danger_redirect' => '' ,
			'danger_callback' => '' ,
			'danger_refresh' => '0' ,
			'danger_modalClose' => '0' ,
			'danger_updater' => '' ,
		];

		foreach($default as $item => $value) {
			if(!isset($setting[$item]))
				$setting[$item] = $value ;
		}

		//Action...
		if($is_saved)
			return $this->jsonFeedback(null,[
				'ok' => '1',
				'message' => $setting['success_message'],
				'redirect' => $setting['success_redirect'],
				'callback' => $setting['success_callback'],
				'refresh' => $setting['success_refresh'],
				'modalClose' => $setting['success_modalClose'],
				'updater' => $setting['success_updater'],
				'redirectTime' => 1000,
			]);
		else {
			return $this->jsonFeedback([
				'ok' => '0',
				'message' => $setting['danger_message'],
				'redirect' => $setting['danger_redirect'],
				'callback' => $setting['danger_callback'],
				'refresh' => $setting['danger_refresh'],
				'modalClose' => $setting['danger_modalClose'],
				'updater' => $setting['danger_updater'],
				'redirectTime' => 1000,
			]);
		}

	}

	private function jsonAjaxSaveFeedback($is_saved, $setting = [])
	{
		//Preferences...
		$default = [
			'success_message' => trans('forms.feed.done') ,
			'success_redirect' => '' ,
			'success_callback' => '' ,
			'success_refresh' => '0' ,
			'success_modalClose' => '1' ,
			'success_updater' => '' ,
			'redirectTime' => 1000,

			'danger_message' => trans('validation.invalid'),
			'danger_redirect' => '' ,
			'danger_callback' => '' ,
			'danger_refresh' => '0' ,
			'danger_modalClose' => '0' ,
			'danger_updater' => '' ,
		];

		foreach($default as $item => $value) {
			if(!isset($setting[$item]))
				$setting[$item] = $value ;
		}

		//Action...
		return $this->jsonSaveFeedback($is_saved,$setting);

	}

}