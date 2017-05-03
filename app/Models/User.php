<?php

namespace App\Models;

use App\Providers\DummyServiceProvider;
use App\Traits\PermitsTrait;
use App\Traits\TahaModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\DB;


class User extends Authenticatable
{
	use Notifiable, TahaModelTrait, PermitsTrait, SoftDeletes;

	public static $meta_fields     = [
		'preferences',
		'name_father',
		'home_tel',
		'postal_code',
		'address',
		'reset_token',
	];
	public static $search_fields   = ['name_first', 'name_last', 'name_firm', 'code_melli', 'email', 'mobile'];
	public static $required_fields = ['name_first', 'name_last', 'code_melli', 'mobile', 'home_tel', 'birth_date', 'gender', 'marital'];
	protected     $guarded         = ['id'];
	protected     $hidden          = ['password', 'remember_token'];
	protected     $casts           = [
		'meta'                  => 'array',
		'newsletter'            => 'boolean',
		'password_force_change' => 'boolean',
		'published_at'          => 'datetime',
		'marriage_date'         => 'datetime',
		'birth_date'            => 'datetime',
	];


	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	|
	*/
	public function roles()
	{
		return $this->belongsToMany('App\Models\Role')->withPivot('permissions', 'deleted_at')->withTimestamps();
	}

	public function receipts()
	{
		return $this->hasMany('App\Models\Receipt');

		//return Receipt::where('user_id', $this->id)->get(); //wrong
	}

	public function comments()
	{
		return $this->hasMany('App\Models\Comment');
	}

	/*
	|--------------------------------------------------------------------------
	| Cache Management
	|--------------------------------------------------------------------------
	|
	*/
	public function cacheUpdate()
	{
		$this->cacheUpdateReceipts();
	}

	public function cacheUpdateReceipts()
	{
		$this->total_receipts_count  = $this->receipts()->count();
		$this->total_receipts_amount = $this->receipts()->sum('purchased_amount');
		$this->update();
	}


	/*
	|--------------------------------------------------------------------------
	| Selectors
	|--------------------------------------------------------------------------
	|
	*/
	public static function selector($parameters = [])
	{
		extract(array_normalize($parameters, [
			'id'       => "0",
			'role'     => "customer",
			'criteria' => "actives",
			'search'   => "",
		]));

		/*-----------------------------------------------
		| Process Roles...
		*/
		if($role == 'all') {
			$table         = User::where('id', '>', '0');
			$related_table = false;
		}
		else {
			$table         = Role::findBySlug($role)->users();
			$related_table = true;
		}

		/*-----------------------------------------------
		| Exclude Developers ...
		*/
		if(!user()->isDeveloper()) {
			$table = $table->whereNotIn('email', ['chieftaha@gmail.com', 'mr.mhrezaei@gmail.com']);
		}

		/*-----------------------------------------------
		| Process id ...
		*/
		if($id > 0) {
			$table = $table->where('users.id', $id);
		}


		/*-----------------------------------------------
		| Process Criteria ...
		*/
		switch ($criteria) {
			case 'all' :
				//nothing to do :)
				break;

			case 'actives':
				if($related_table) {
					$table = $table->wherePivot('deleted_at', null);
				}
				else {
					//nothing to do <~~
				}
				break;

			case 'banned':
				if($related_table) {
					$table = $table->wherePivot('deleted_at', '!=', null);
				}
				else {
					$table = $table->onlyTrashed();
				}
				break;

			case 'bin' :
				if($related_table) {
					$table = $table->wherePivot('deleted_at', '!=', null);
				}
				else {
					$table = $table->onlyTrashed();
				}
				break;

			default:
				if($related_table) {
					$table = $table->where('users.id', '0');
				}
				else {
					$table = $table->where('id', 0);
				}

		}

		/*-----------------------------------------------
		| Process Search ...
		*/
		$table = $table->whereRaw(self::searchRawQuery($search));


		/*-----------------------------------------------
		| Return  ...
		*/

		return $table;
	}


	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/

	public function getMobileMaskedAttribute()
	{
		$string = $this->mobile;
		if(strlen($string) == 11) {
			return substr($string, 7) . ' ••• ' . substr($string, 0, 4);
		}

		return $string;
	}

	public function getMobileFormattedAttribute()
	{
		$string = $this->mobile;
		if(strlen($string) == 11) {
			return substr($string, 7) . ' - ' . substr($string, 4, 3) . ' - ' . substr($string, 0, 4);
		}

		return $string;
	}


	public function getFullNameAttribute()
	{
		if($this->exists) {
			return $this->name_first . ' ' . $this->name_last;
		}
		else {
			return trans('people.deleted_user');
		}
	}

	public function getStatusAttribute()
	{
		$request_role = $this->getAndResetAsRole();
		if($request_role) {
			if(!$this->as($request_role)->includeDisabled()->hasRole()) {
				return 'without';
			}
			elseif($this->enabled()) {
				return 'active';
			}
			else {
				return 'blocked';
			}
		}
		else {
			if(!$this->trashed()) {
				return 'active';
			}
			else {
				return 'blocked';
			}
		}

	}

	public function getSumReceiptAmountAttribute()
	{
		return Receipt::where('user_id', $this->id)->sum('purchased_amount');
	}


	//public function getAdminPositionAttribute()
	//{
	//	if(!$this->hasRole('admin'))
	//		return '-' ;
	//
	//	if($this->isDeveloper())
	//		return trans('people.admins.developer');
	//	if($this->as('admin')->can('super'))
	//		return trans('people.admins.super_admin');
	//	else
	//		return trans('people.admins.ordinary_admin');
	//}


	/*
	|--------------------------------------------------------------------------
	| Stators
	|--------------------------------------------------------------------------
	|
	*/

	public function preference($slug)
	{
		$this->spreadMeta();
		$preferences = array_normalize($this->preferences, [
			'max_rows_per_page' => "50",
		]);

		return $preferences[ $slug ];
	}

	public function canEdit()
	{
		$request_role = $this->getAndResetAsRole();

		/*-----------------------------------------------
		| Power users ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		elseif($this->is_an('admin')) {
			return user()->is_a('superadmin');
		}

		/*-----------------------------------------------
		| Other Users ...
		*/
		if(!$request_role or $request_role == 'admin') {
			return user()->is_a('superadmin');
		}
		else {
			return Role::checkManagePermission($request_role, 'edit');
		}
	}

	public function canDelete()
	{
		$request_role = $this->getAndResetAsRole();

		/*-----------------------------------------------
		| Power users ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		elseif($this->is_an('admin')) {
			return user()->is_a('superadmin');
		}

		/*-----------------------------------------------
		| Other users ... @TODO: complete this part
		*/

		return user()->is_a('superadmin');

	}

	public function canBin()
	{
		$request_role = $this->getAndResetAsRole();

		/*-----------------------------------------------
		| Power users ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		elseif($this->is_an('admin')) {
			return user()->is_a('superadmin');
		}

		/*-----------------------------------------------
		| Other users ... @TODO: complete this part
		*/

		return user()->is_a('superadmin');

	}


	public function canPermit()
	{
		$request_role = $this->getAndResetAsRole();

		/*-----------------------------------------------
		| Simple Decisions ...
		*/
		if($this->trashed()) {
			return false;
		}
		if($this->id == user()->id) {
			return false;
		}

		if($request_role) {
			if($this->as($request_role)->disabled()) {
				return false;
			}
			$role = Role::findBySlug($request_role);
			if(!$role or !$role->has_modules) {
				return false;
			}
		}

		/*-----------------------------------------------
		| Power users ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		elseif($this->is_an('admin')) {
			return user()->is_a('superadmin');
		}

		/*-----------------------------------------------
		| Other users ... @TODO: complete this part
		*/

		return user()->is_a('superadmin');
	}


	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public function rolesTable()
	{
		return Role::all();
	}

	public function roleStatusCombo()
	{
		return [
			['', trans('people.without_role')],
			['active', trans('forms.status_text.active')],
			['blocked', trans('forms.status_text.blocked')],
		];

	}

	public function totalReceiptsAmountInEvent($post)
	{
		return $post->receipts->where('user_id', $this->id)->sum('purchased_amount');
	}

    public function drawingRecentScores($eventsNumber, $historyLimit = 0)
    {
//        $locales = ['en', 'fa', 'ar'];
//        $prices = [50000, 100000, 20000, 15000, 73000];
//        for ($i = 1; $i < 200; $i++) {
//            $data = [
//                'type' => 'events',
//                'locale' => $locales[$i % (count($locales))],
//                'title' => DummyServiceProvider::persianTitle(),
//                'starts_at' => Carbon::parse('2017-04-10')->addDays($i % 5)->toDateTimeString(),
//                'ends_at' => Carbon::parse('2017-04-23')->addDays($i % 5)->toDateTimeString(),
//                'moderate_note' => null,
//                'title2' => '',
//                'rate_point' => $prices[$i % (count($prices))],
//            ];
//
//            Post::store($data);
//        }
//        die();

        return Post::where([
            'type' => 'events',
            'locale' => getLocale(),
        ])->whereDate('starts_at', '<=', Carbon::now())
            ->whereDate('ends_at', '>=', Carbon::now()->subDay($historyLimit))
            ->leftJoin('receipts', [
                ['starts_at', '<=', 'purchased_at'],
                ['ends_at', '>=', 'purchased_at'],
                ['user_id', '=', DB::raw(user()->id)]
            ])
            ->select(DB::raw('posts.*, sum(receipts.purchased_amount) as sum_amount'))
            ->groupBy(DB::raw('posts.id'))
            ->limit($eventsNumber)
            ->orderBy('sum_amount', 'DESC')
            ->get();

    }
}
