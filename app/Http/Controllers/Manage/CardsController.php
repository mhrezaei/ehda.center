<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\CardInquiryRequest;
use App\Http\Requests\Manage\CardSaveRequest;
use App\Http\Requests\Manage\SearchRequest;
use App\Models\Post;
use App\Models\Printer;
use App\Models\Printing;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use App\Providers\YasnaServiceProvider;
use App\Traits\ManageControllerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\jDate;


class CardsController extends UsersController
{
	use ManageControllerTrait;

	protected $Model;
	protected $browse_counter;
	protected $browse_selector;
	protected $view_folder;

	protected $role_slug = 'card-holder';
	protected $role;
	protected $url;
	protected $grid_row;
	protected $grid_array;
	protected $toolbar_buttons;

	public function __construct()
	{
		$this->page[0] = ['users', trans('people.site_users')];

		$this->Model = new User();
		$this->Model->setSelectorPara([
			'role' => "admin",
		]);

		$this->browse_handle = 'counter';
		$this->view_folder   = 'manage.users';
		$this->role          = Role::findBySlug($this->role_slug);

		return parent::__construct();
	}

	public function browseSwitchesChild()
	{
		return [
			//'role_slug'       => $this->role_slug,
			'url'               => "cards/browse",
			'grid_row'          => "browse-row-for-cards",
			'grid_array'        => [
				trans('validation.attributes.name_first'),
				trans("ehda.cards.register"),
				trans('validation.attributes.home_city'),
				trans('forms.button.action'),
			],
			'toolbar_buttons'   => [
				[
					'target'    => "manage/cards/create",
					'type'      => 'success',
					'condition' => user()->as('admin')->can('users-card-holder.create'),
					'icon'      => 'plus-circle',
					'caption'   => trans("ehda.cards.create"),
				],
			],
			'more_mass_actions' => [
				['print', trans('ehda.printings.send_to'), "modal:manage/users/act/0/card-print"],
			],
			//'search_panel_view' => "search-for-cards",
		];

	}

	public function update($model_id, $request_role = null)
	{
		$request_role = $this->role_slug;
		$model        = User::withTrashed()->find($model_id);
		$handle       = 'selector';

		//Run...
		if(!$model) {
			return view('errors.m410');
		}
		else {
			$model->spreadMeta();
		}

		return view($this->view_folder . '.' . $this->browseSwitchesChild()['grid_row'], compact('model', 'handle', 'request_role'));
	}


	public function browseChild($request_tab = 'all')
	{
		return $this->browse($this->role_slug, $request_tab, $this->browseSwitchesChild());
	}

	public function searchChild(SearchRequest $request)
	{
		return $this->search($this->role_slug, $request, $this->browseSwitchesChild());
	}

	public function editorChild($model_hash_id)
	{
		/*-----------------------------------------------
		| Model ...
		*/
		$model = User::findByHashid($model_hash_id);
		if(!$model or !$model->id or $model->is_not_a('card-holder')) {
			return view('errors.410');
		}
		if(!$model->canEdit()) {
			return view('errors.403');
		}

		$model->spreadMeta();
		$states = State::combo();

		$all_events = Post::selector([
			'type'   => "event",
			'domain' => "auto",
		])->orderBy('published_at', 'desc')->get()
		;

		$events = [];
		foreach($all_events as $event) {
			if($event->spreadMeta()->can_register_card) {
				$events[] = $event;
			}
		}

		$model->event_id = session()->get('user_last_used_event', 0);

		/*-----------------------------------------------
		| Page Preparations ...
		*/
		$page    = $this->page;
		$page[0] = ['cards/browse', $this->role->plural_title];
		$page[1] = ["cards/edit/$model_hash_id", trans("ehda.cards.edit")];


		/*-----------------------------------------------
		| View ...
		*/

		return view("manage.users.card-editor", compact('page', 'model', 'states', 'events'));
	}


	public function createChild($given_code_melli = false)
	{
		/*-----------------------------------------------
		| Permission ...
		*/
		if(user()->as('admin')->cannot('users-card-holder.create')) {
			return view('errors.403');
		}

		/*-----------------------------------------------
		| Preparations ...
		*/
		$page    = $this->page;
		$page[0] = ['cards/browse', $this->role->plural_title];
		$page[1] = ['cards/create', trans("ehda.cards.create")];

		/*-----------------------------------------------
		| If a Code Melli is Given ...
		*/
		if(!YasnaServiceProvider::isCodeMelli($given_code_melli)) {
			$given_code_melli = false;
		}
		else {
			$found_model = userFinder($given_code_melli);
			if($found_model->id and $found_model->is_not_a('card-holder')) {
				$model = $found_model;
			}
			else {
				$given_code_melli = false;
			}
		}

		/*-----------------------------------------------
		| Model ...
		*/
		if(!isset($model)) {
			$model = new User();
		}
		$states = State::combo();

		$model->newsletter = 1;
		$model->code_melli = $given_code_melli;

		$all_events = Post::selector([
			'type'   => "event",
			'domain' => "auto",
		])->orderBy('published_at', 'desc')->get()
		;
		$events     = [];
		foreach($all_events as $event) {
			if($event->spreadMeta()->can_register_card) {
				$events[] = $event;
			}
		}

		$model->event_id = session()->get('user_last_used_event', 0);


		/*-----------------------------------------------
		| View ...
		*/

		return view("manage.users.card-editor", compact('page', 'model', 'states', 'events'));

	}

	public function inquiry(CardInquiryRequest $request)
	{
		$user = userFinder($request->code_melli);

		/*-----------------------------------------------
		| If not found ...
		*/
		if(!$user or !$user->id) {
			return $this->jsonFeedback(1, [
				'ok'           => 1,
				'message'      => trans('ehda.cards.inquiry_success'),
				'callback'     => 'cardEditor(1)',
				'redirectTime' => 1,
			]);
		}

		/*-----------------------------------------------
		| If already has card ...
		*/
		if($user->is_a('card-holder')) {
			return $this->jsonFeedback(1, [
				'ok'           => 1,
				'message'      => trans('ehda.cards.inquiry_has_card'),
				'callback'     => 'cardEditor(2 , "' . $user->hash_id . '")',
				'redirectTime' => 1,
			]);
		}

		/*-----------------------------------------------
		| If a volunteer without card ...
		*/
		if($user->min(8)->is_an('admin') and $user->is_not_a('card-holder')) {
			return $this->jsonFeedback(1, [
				'ok'           => 1,
				'message'      => trans('ehda.cards.inquiry_is_volunteer'),
				'redirect'     => url("manage/cards/create/$user->code_melli"),
				'redirectTime' => 1,
			]);
		}

		if($user->max(6)->is_an('admin')) {
			return $this->jsonFeedback(1, [
				'ok'       => 1,
				'message'  => trans('ehda.cards.inquiry_is_volunteer'),
				'redirect' => Auth::user()->can('cards.create') ? url("manage/cards/create/$user->code_melli") : '',
			]);

		}

	}

	public function saveChild(CardSaveRequest $request)
	{
		/*-----------------------------------------------
		| Model ...
		*/
		$data = $request->toArray();

		if($request->id) {
			unset($data['event_id']);
			$model = User::find($request->id);
			if(!$model or !$model->id) {
				return $this->jsonFeedback(trans('validation.http.Error410'));
			}
			if(!$model->canEdit()) {
				return $this->jsonFeedback(trans('validation.http.Error403'));
			}
		}

		/*-----------------------------------------------
		| Processing Dates ...
		*/
		$carbon             = new Carbon($request->birth_date);
		$data['birth_date'] = $carbon->toDateString();

		/*-----------------------------------------------
		| Processing States ...
		*/
		$home_city = State::find($request->home_city);
		if($home_city) {
			$data['home_province'] = $home_city->parent_id;
		}
		else {
			$data['home_city']     = 0;
			$data['home_province'] = 0;
		}

		$work_city = State::find($request->work_city);
		if($work_city) {
			$data['work_province'] = $work_city->parent_id;
		}
		else {
			$data['work_province'] = 0;
			$data['home_province'] = 0;
		}


		/*-----------------------------------------------
		| Processing passwords ...
		*/
		if(!$data['id'] or $data['_password_set_to_mobile']) {
			$data['password']              = Hash::make($data['mobile']);
			$data['password_force_change'] = 1;
		}

		/*-----------------------------------------------
		| Processing Domain ...
		*/
		$data['domain'] = user()->domain;
		if(!$data['domain'] or $data['domain'] == 'global') {
			$state = State::find(user()->home_city);
			if($state) {
				$data['domain'] = $state->domain->slug;
			}
		}

		/*-----------------------------------------------
		| Register Date and Card No...
		*/
		if(!$request->id or (isset($model) and $model->is_not_a('card-holder'))) {
			$data['card_registered_at'] = Carbon::now()->toDateTimeString();
		}


		/*-----------------------------------------------
		| user_last_used_event ...
		*/
		session()->put('user_last_used_event', $request->event_id);

		/*-----------------------------------------------
		| Save ...
		*/
		$saved = User::store($data);

		/*-----------------------------------------------
		| Role...
		*/
		if($saved) {
			$saved_user = User::find($saved);
			if($saved_user and $saved_user->id and $saved_user->is_not_a('card-holder')) {
				$saved_user->update([
					'card_no' => $saved_user->generateCardNo(),
				]);
				$saved_user->attachRole('card-holder');
			}
		}

		/*-----------------------------------------------
		| Send to Print ...
		*/
		Printer::where('user_id', $saved_user->id)->delete();
		if($saved and $data['_submit'] == 'print') {
			$saved = Printing::addTo(Post::find($request->from_event_id), $saved_user);
		}

		/*-----------------------------------------------
		| Return ...
		*/

		return $this->jsonAjaxSaveFeedback($saved, ['success_refresh' => true,]);

	}

	/*
	|--------------------------------------------------------------------------
	| Printings
	|--------------------------------------------------------------------------
	|
	*/
	public function printingBrowse($request_tab = 'pending', $event_id = 0, $user_id = 0, $volunteer_id = 0)
	{
		/*-----------------------------------------------
		| Preparations ...
		*/
		if(!in_array($request_tab, ['pending', 'under_direct_printing', 'under_excel_printing'])) {
			return view('errors.404');
		}

		if(user()->as('admin')->cannot('users-card-holder.print')) {
			return view('errors.403');
		}
		if($request_tab == 'under_direct_printing' or $request_tab == 'under_excel_printing') {
			$permit = str_replace("under_", null, str_replace("_printing", null, $request_tab));
			if(user()->as('admin')->cannot("users-card-holder.print-" . $permit)) {
				return view('errors.403');
			}
		}

		/*-----------------------------------------------
		| Page ...
		*/
		$page[0] = ['cards/printings', trans("ehda.printings.title"), 'cards/printings'];
		$page[1] = [$request_tab, trans("ehda.printings.$request_tab"), "cards/printings/$request_tab"];

		/*-----------------------------------------------
		| Events Menu ...
		*/
		$all_events   = Post::getAllEvents();
		$event_title  = trans('ehda.printings.all_events');
		$events_array = [
			[
				$event_id == 0 ? 'check' : '',
				$event_title,
				url("manage/cards/printings/$request_tab/all"),
			],
		];

		foreach($all_events as $event) {
			if($event_id == $event->id) {
				$event_title = $event->title;
			}
			array_push($events_array, [
				$event_id == $event->id ? 'check' : '',
				$event->title,
				url("manage/cards/printings/$request_tab/$event->id"),
			]);
		}

		/*-----------------------------------------------
		| Printings Model ...
		*/
		$models = Printing::selector([
			'criteria'   => $request_tab,
			'event_id'   => $event_id,
			'user_id'    => $user_id,
			'created_by' => $volunteer_id,
			//'domain'     => "auto",
		])->orderBy('updated_at', 'desc')->paginate(50)
		;

		$db = new Printing();

		/*-----------------------------------------------
		| View ...
		*/

		return view("manage.printings.browse", compact('page', 'events_array', 'event_title', 'models', 'db', 'request_tab', 'volunteer', 'volunteer_id', 'event_id', 'user_id'));

	}

	public function printingAction($action)
	{
		$view = "manage.printings.act-$action";

		if($action == 'add-to-excel' or $action == 'add-to-direct') {
			$additive = str_replace('add-to-', null, $action);
			if(user()->as('admin')->cannot("users-card-holder.print-$additive")) {
				return view('errors.m403');
			}
		}

		//if(!View::exists($view)) {
		//	return view('errors.m404');
		//}

		return view($view);

	}

	public function printingActionSave(Request $request)
	{
		$action   = $request->_submit;
		$callback = null;

		/*-----------------------------------------------
		| Available Actions ...
		*/
		if(!in_array($action, ['add-to-direct', 'add-to-excel', 'confirm-quality', 'revert-to-pending', 'cancel'])) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		/*-----------------------------------------------
		| Security ...
		*/
		if(in_array($action, ['add-to-direct', 'add-to-excel'])) {
			$additive = str_replace('add-to-', null, $action);
			if(user()->as('admin')->cannot("users-card-holder.print-$additive")) {
				return $this->jsonFeedback(trans('validation.http.Error403'));
			}
		}

		/*-----------------------------------------------
		| Model ...
		*/
		if(in_array($action, ['add-to-direct', 'add-to-excel']) and $request->select_all) {
			$table = Printing::selector([
				'event_id' => $request->browse_event_id,
				'criteria' => "pending",
			]);

		}
		else {
			$table = Printing::whereIn('id', explode(',', $request->ids));
		}

		/*-----------------------------------------------
		| Action ...
		*/
		$now      = Carbon::now()->toDateTimeString();
		$admin_id = user()->id;
		switch ($action) {
			case 'cancel' :
				$data = [
					'deleted_at' => $now,
					'deleted_by' => $admin_id,
				];
				break;
			case 'add-to-direct' :
				$data = [
					'printed_at'    => null,
					'queued_at'     => $now,
					'verified_at'   => null,
					'dispatched_at' => null,
					'delivered_at'  => null,
					'printed_by'    => 0,
					'queued_by'     => $admin_id,
					'verified_by'   => 0,
					'dispatched_by' => 0,
					'delivered_by'  => 0,
				];
				$this->printingActionSave_direct($table);
				break;
			case 'add-to-excel' :
				$data = [
					'printed_at'    => $now,
					'queued_at'     => $now,
					'verified_at'   => null,
					'dispatched_at' => null,
					'delivered_at'  => null,
					'printed_by'    => $admin_id,
					'queued_by'     => $admin_id,
					'verified_by'   => 0,
					'dispatched_by' => 0,
					'delivered_by'  => 0,
				];
				session()->put('excelDownload', true);
				//$callback = "$('#btnDownloadExcel').change()";
				break;
			case 'confirm-quality' :
				$data = [
					'printed_at'    => $now,
					'verified_at'   => $now,
					'dispatched_at' => $now,
					'delivered_at'  => $now,
					'printed_by'    => $admin_id,
					'verified_by'   => $admin_id,
					'dispatched_by' => $admin_id,
					'delivered_by'  => $admin_id,
				];
				$this->printingActionSave_revert($table); // <~~ to safely delete the relevant rows in `printers` table
				break;
			case 'revert-to-pending' :
				$data = [
					'printed_at'    => null,
					'queued_at'     => null,
					'verified_at'   => null,
					'dispatched_at' => null,
					'delivered_at'  => null,
					'printed_by'    => 0,
					'queued_by'     => 0,
					'verified_by'   => 0,
					'dispatched_by' => 0,
					'delivered_by'  => 0,
				];
				$this->printingActionSave_revert($table);
				break;

			default:
				return $this->jsonFeedback(trans('validation.http.Error410'));


		}

		/*-----------------------------------------------
		| Execution and Return ...
		*/

		return $this->jsonAjaxSaveFeedback($table->update($data), [
			'success_refresh'  => 1,
			'success_callback' => $callback,
		]);

	}

	protected function printingActionSave_revert($table)
	{
		$ids = $table->pluck('id')->toArray();
		Printer::whereIn('printing_id', $ids)->delete();
	}

	protected function printingActionSave_direct($table)
	{
		foreach($table->get() as $row) {
			$user = $row->user;
			if(!$user or !$user->id) {
				continue;
			}

			Printer::where('user_id', $user->id)->delete();
			if(Printer::where('user_id', $user->id)->count()) {
				continue;
			}

			Printer::create([
				'user_id'       => $user->id,
				'printing_id'   => $row->id,
				'name_full'     => $user->full_name,
				'name_father'   => $user->name_father,
				'code_melli'    => pd($user->code_melli),
				'birth_date'    => $user->birth_date_on_card,
				'card_no'       => pd($user->card_no),
				'registered_at' => $user->register_date_on_card,
			]);
		}

		return;
	}

	protected function printingExcelDownload($event_id)
	{
		$event_id = intval($event_id);
		session()->put('excel_event_id', $event_id);

		Excel::create('Cards-To-Excel-For-Hard-Print', function ($excel) {
			$excel->sheet('print', function ($sheet) {

				$sheet->loadView('manage.printings.excel_file');

			});


		})->download('xls')
		;

	}

	public function addToPrintings(Request $request)
	{
		/*-----------------------------------------------
		| Security ...
		*/
		if(user()->as('admin')->cannot('users-card-holder')) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		/*-----------------------------------------------
		| Model ...
		*/
		$user  = User::find($request->user_id);
		$event = Post::find($request->event_if_for_print);
		if(!$user or !$user->id or !$event or !$event->id) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		/*-----------------------------------------------
		| Action ...
		*/
		session()->put('user_last_used_event', $event->id);
		$saved = Printing::addTo($event, $user);

		return $this->jsonAjaxSaveFeedback($saved);

	}

	public function addToPrintingsMass(Request $request)
	{
		$id_array = explode(',', $request->ids);
		$done     = 0;

		/*-----------------------------------------------
		| Security ...
		*/
		if(user()->as('admin')->cannot('users-card-holder')) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		/*-----------------------------------------------
		| Action ...
		*/
		$event = Post::find($request->event_if_for_print);
		session()->put('user_last_used_event', $event->id);
		foreach($id_array as $id) {
			$user = User::find($id);
			if($user and $user->id) {
				$done += boolval(Printing::addTo($event, $user));
			}
		}

		/*-----------------------------------------------
		| Return ...
		*/

		return $this->jsonAjaxSaveFeedback($done, [
			'success_message' => trans("forms.feed.mass_done", [
				"count" => pd($done),
			]),
		]);


	}

	public function view($model_hashid)
	{
		/*-----------------------------------------------
		| Security ...
		*/
		if(user()->as('admin')->cannot('users-card-holder.view')) {
			return view('errors.m403');
		}

		/*-----------------------------------------------
		| Model ...
		*/
		$model = User::findByHashid($model_hashid);
		if(!$model or !$model->id or $model->is_not_a('card-holder')) {
			return view('errors.m410');
		}

		/*-----------------------------------------------
		| View ...
		*/

		return view("manage.users.card-view", compact('model'));


	}

	public function eventStats($post_hashid)
	{
		/*-----------------------------------------------
		| Model ...
		*/
		$model = Post::findByHashid($post_hashid);
		if(!$model or !$model->id) {
			return view('errors.m410');
		}


		$total_count     = $model->registers()->count();
		$first_card      = $model->registers()->orderBy('created_at')->first();
		$last_card       = $model->registers()->orderBy('created_at', 'desc')->first();
		$first_print     = $model->printings()->orderBy('created_at')->first();
		$last_print      = $model->printings()->orderBy('created_at', 'desc')->first();
		$daily_registers = [];

		/*-----------------------------------------------
		| Model ...
		*/
		//if($total_count>0) {
		$today    = min($first_card->created_at->startOfDay(), $first_print->created_at->startOfDay());
		$tomorrow = min($first_card->created_at->startOfDay(), $first_print->created_at->startOfDay())->addDay();

		while ($today < max($last_card->created_at, $last_print->created_at)) {
			$count1            = $model->registers()
			                           ->where('created_at', '>=', $today)
			                           ->where('created_at', '<', $tomorrow)
			                           ->count()
			;
			$count2            = $model->printings()
			                           ->where('created_at', '>=', $today)
			                           ->where('created_at', '<', $tomorrow)
			                           ->count()
			;
			$daily_registers[] = [$today->toDateTimeString(), $count1, $count2];

			$today->addDay();
			$tomorrow->addDay();
		}
		//}

		/*-----------------------------------------------
		| View ...
		*/

		return view('manage.users.cards-event-stats', compact('model', 'total_count', 'daily_registers'));

	}

	public function registerStatsPanel()
	{
		return view('manage.users.cards-register-stats') ;
	}


	public function registerStatsResult($date = false, $days = 31)
	{
		$daily_registers = [];
		$total_registers = [
			'count_total' => 0 ,
		     'count_site' => 0 ,
		     'count_bot' => 0 ,
		     'count_volunteer' => 0 ,
		];

		/*-----------------------------------------------
		| Dates...
		*/
		if(!$date) {
			$last_date = Carbon::now()->setTime(0, 0);
		}
		else {
			$last_date = Carbon::createFromFormat("Y-m-d" , $date)->setTime(0,0) ;
		}

		$first_date = clone $last_date;
		$first_date->subDay($days);


		/*-----------------------------------------------
		| Loop ...
		*/
		$pointer = clone $last_date;
		while ($pointer >= $first_date) {
			$day_after_pointer = clone $pointer;
			$day_after_pointer->addDay(1);

			$count_total     = User::where('card_registered_at', '>=', $pointer)
			                       ->where('card_registered_at', '<', $day_after_pointer)
			                       ->count()
			;
			$count_site      = User::where('card_registered_at', '>=', $pointer)
			                       ->where('card_registered_at', '<', $day_after_pointer)
			                       ->where('created_by', 0)
			                       ->count()
			;
			$count_bot       = User::where('card_registered_at', '>=', $pointer)
			                       ->where('card_registered_at', '<', $day_after_pointer)
			                       ->whereIn('created_by', model('user')::apiBots())
			                       ->count()
			;
			$count_volunteer = $count_total - $count_site - $count_bot;
			$date            = jDate::forge($pointer)->format('j F Y');

			$pointer->subDay(1);
			$daily_registers[] = compact('date', 'count_total', 'count_site', 'count_bot', 'count_volunteer');
			$total_registers['count_total'] += $count_total;
			$total_registers['count_site'] += $count_site;
			$total_registers['count_bot'] += $count_bot;
			$total_registers['count_volunteer'] += $count_volunteer;
		}


		/*-----------------------------------------------
		| Return ...
		*/
		return view('manage.users.cards-register-stats-result', compact('first_date', 'last_date', 'daily_registers' , 'total_registers'));


	}


	public function deleteChild(Request $request)
	{
		/*-----------------------------------------------
		| Model ...
		*/
		$model = User::find($request->user_id);
		if(!$model or !$model->exists or $model->is_not_a('card-holder')) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		/*-----------------------------------------------
		| Security ...
		*/
		if(user()->as('admin')->cannot('users-card-holder.delete')) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		/*-----------------------------------------------
		| Deletion ...
		*/
		if(count($model->rolesArray()) > 1) {
			$model->detachRole('card-holder');
			$ok = User::store([
				'id'                 => $model->id,
				'card_registered_at' => null,
				'card_no'            => 0,
			]);
		}
		else {
			$ok = $model->delete();
		}

		/*-----------------------------------------------
		| Feedback ...
		*/

		return $this->jsonAjaxSaveFeedback($ok, [
			'success_callback' => "rowHide('tblUsers','$model->id')",
		]);


	}


}
