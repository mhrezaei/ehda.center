<?php

namespace App\Http\Controllers\Manage;

use App\Models\Category;
use App\Models\Domain;
use App\Models\Folder;
use App\Models\MetaOld;
use App\Models\Post;
use App\Models\PostsOld;
use App\Models\PrintingsOld;
use App\Models\Role;
use App\Models\Setting;
use App\Models\State;
use App\Models\User;
use App\Models\UsersOld;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;


class ConvertController extends Controller
{
	public function index()
	{
		$this->testRole();
		//$this->createTaha() ;
		//$this->reset() ;
		//return $this->users();
		//$this->posts() ;
		//$this->postsMeta();
		//return $this->createRoles() ;
	}

	public function createTaha()
	{
		User::where('code_melli', "0074715623")->update(['password' => bcrypt('11111111')]);
		//DB::table('users')->insert([
		//	[
		//		'code_melli' => "0074715623",
		//		'email' => "chieftaha@gmail.com",
		//		'name_first' => "طاها",
		//		'name_last' => "کامکار",
		//		'password' => bcrypt('11111111'),
		//		'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
		//	]]);
		//return ;

	}

	public function reset()
	{
		//User::where('id','>','0')->forceDelete() ;
	}

	public function createRoles()
	{
		$domains = Domain::all();
		foreach($domains as $domain) {
			Role::create([
				'slug'         => 'volunteer-' . $domain->slug,
				'title'        => "سفیر " . $domain->title,
				'plural_title' => "سفیران " . $domain->title,
				'modules'      => "{\"posts\":[\"create\",\"edit\",\"publish\",\"report\",\"delete\",\"bin\"],\"users\":[\"browse\",\"search\",\"create\",\"edit\",\"publish\",\"report\",\"delete\",\"bin\",\"settings\",\"permit\"],\"cards\":[\"browse\",\"search\",\"create\",\"edit\",\"report\",\"delete\",\"bin\"],\"volunteers\":[\"browse\",\"search\",\"create\",\"edit\",\"report\",\"delete\",\"bin\",\"settings\",\"permit\"],\"comments\":[\"edit\",\"process\",\"publish\",\"report\",\"delete\",\"bin\"]}",
				'is_admin'     => '1',
				'meta'         => "{\"icon\":\"\",\"status_rule\":{\"1\":\"under_examination\",\"2\":\"waiting_for_data_completion\",\"3\":\"pending\",\"8\":\"active\"},\"fields\":\"\"}",
			]);
		}

		Role::create([
			'slug'         => "card-holder",
			'title'        => "کارت اهدای عضو",
			'plural_title' => "کارت‌های اهدای عضو",
		]);

		Setting::set('default_role', 'card-holder');
	}

	public function postsMeta($take = 50, $loop = true)
	{
		$metas        = MetaOld::where('converted', '0')->where('model_name', 'Post')->take($take)->get();
		$last_meta_id = 0;
		$counter      = 0;

		foreach($metas as $meta) {

			//ss($meta->toArray()) ;

			$post = Post::withTrashed()->find($meta->record_id);
			if(!$post) {
				ss("Post $meta->record_id Not Found!");
				$meta->update([
					'converted' => "1",
				]);
				continue;
			}

			if($meta->key == 'title_two') {
				$post->update([
					'title2' => $meta->value,
				]);
			}
			elseif($meta->key == 'post_photos') {
				$value = json_decode($meta->value, true);
			}
			else {
				$value = $meta->value;
				$post->updateMeta([
					$meta->key => $value,
				], true);
			}

			$meta->update([
				'converted' => "1",
			]);
			$counter++;
			$last_meta_id = $meta->id;

		}

		ss("Counter: $counter");
		ss("Last Post Updated id: " . $last_meta_id);

		//return ;
		if($counter > 0 and $loop) {
			echo "<script>location.reload();</script>";
		}

	}

	public function posts($take = 100, $loop = true)
	{
		$olds         = PostsOld::where('converted', '0')->take($take)->get();
		$last_post_id = 0;
		$counter      = 0;
		$category     = "NOT DEFINED";

		/*-----------------------------------------------
		| Normal Data ...
		*/
		foreach($olds as $old) {
			$domains = "|$old->domains|";
			if($domains == '|free|') {
				$domains = '|global|';
			}
			$data = [
				'id'             => $old->id,
				'slug'           => Post::normalizeSlug(0, $old->branch, 'fa', $old->slug),
				'type'           => $old->branch,
				'title'          => $old->title,
				'locale'         => "fa",
				'is_draft'       => $old->is_draft,
				'sisterhood'     => Hashids::encode($old->id),
				'text'           => $old->text,
				'abstract'       => $old->abstract,
				//'starts_at' => "" ,
				//'ends_at' => "" ,
				'domains'        => $domains,
				'created_at'     => $old->created_at,
				'updated_at'     => $old->updated_at,
				'deleted_at'     => $old->deleted_at,
				'published_at'   => $old->published_at,
				'created_by'     => $old->created_by + 0,
				'updated_by'     => $old->updated_by + 0,
				'deleted_by'     => $old->deleted_by + 0,
				'published_by'   => $old->published_by + 0,
				'owned_by'       => $old->created_by + 0,
				'moderated_by'   => $old->published_by + 0,
				'moderated_at'   => $old->published_at,
				'featured_image' => $old->featured_image,
				'meta'           => "",
			];

			$post = Post::create($data);

			if($old->category_id) {
				switch ($old->category_id) {
					case 1 :
						$category = 7;
						break;
					case 3 :
						$category = 8;
						break;
					case 4 :
						$category = 9;
						break;
					case 5 :
						$category = 11;
						break;
					case 6 :
						$category = 4;
						break;
					case 8 :
						$category = 5;
						break;
					case 9 :
						$category = 1;
						break;
					case 10 :
						$category = 10;
						break;
					case 11 :
						$category = 2;
						break;
					case 12 :
						$category = 3;
						break;
					default :
						$category = 0;
				}
				if($category) {
					$post->saveCategories(["category-" . hashid_encrypt($category) => '1']);
				}
			}

			//ss($post->toArray());
			//ss("featured_image: ".$old->featured_image);
			//ss("category: " . $category);

			$old->update([
				'converted' => "1",
			]);
			$counter++;
			$last_post_id = $post->id;
		}

		ss("Counter: $counter");
		ss("Last Post Created id: " . $last_post_id);

		//return ;
		if($counter > 0 and $loop) {
			echo "<script>location.reload();</script>";
		}

	}

	public function printing($take = 500, $loop = true)
	{
		dd("GO TO HELL, Thank you by the way. :)");
	}

	public static function userRoleCaches($take = 500, $loop = 1)
	{
		$last_time = session()->get('convert_last_time', false);

		if(!$last_time or is_int($last_time)) {
			session()->put('convert_last_time', Carbon::now());
			echo "<script>location.reload();</script>";
		}

		$users        = User::whereNull('cache_roles')->take($take)->get();
		$last_user_id = 0;
		$counter      = 0;

		foreach($users as $user) {
			$user->rolesCacheUpdate();
			$counter++;
			$last_user_id = $user->id;
		}

		ss("Updating Role Caches...");
		ss("Counter: $counter");
		ss("Last Processed id: " . $last_user_id);
		ss("Took " . strval($took = Carbon::now()->diffInSeconds($last_time)) . " Second(s) for $take Record(s). ");

		if($took > $take) {
			ss("(Average: " . strval(round($took / $take, 2)) . " Seconds per Record.) ");
		}
		else {
			ss("(Average: " . strval(round($take / $took, 2)) . " Records per Second.) ");
		}

		//return ;
		if($counter > 0 and $loop) {
			session()->put('convert_last_time', Carbon::now());
			echo "<script>location.reload();</script>";
		}

	}

	public function users($take = 500, $loop = 1)
	{
		$last_time = session()->get('convert_last_time', false);

		if(!$last_time or is_int($last_time)) {
			session()->put('convert_last_time', Carbon::now());
			echo "<script>location.reload();</script>";
		}

		$olds         = UsersOld::where('converted', '0')->orderBy('id')->take($take)->get();
		$last_user_id = 0;
		$counter      = 0;

		foreach($olds as $old) {
			$data = $old->toArray();

			/*-----------------------------------------------
			| Unset Old Fields ...
			*/
			unset($data['volunteer_status']);
			unset($data['card_status']);
			unset($data['tel_mobile']);
			unset($data['home_postal_code']);
			unset($data['work_postal_code']);
			unset($data['remember_token']);
			unset($data['organs']);
			unset($data['familization']);
			unset($data['settings']);
			unset($data['domains']);
			unset($data['roles']);
			unset($data['card_print_status']);
			unset($data['event_id']);
			unset($data['converted']);

			/*-----------------------------------------------
			| Set Simple Things ...
			*/
			$data['mobile']          = $old->tel_mobile;
			$data['home_postal']     = $old->home_postal_code;
			$data['work_postal']     = $old->work_postal_code;
			$data['familiarization'] = $old->familization;
			$data['from_event_id']   = $old->event_id;

			$data['exam_result']   = $old->exam_result + 0;
			$data['from_event_id'] = $old->event_id + 0;
			$data['gender']        = $old->gender + 0;
			$data['marital']       = $old->marital + 0;
			$data['deleted_by']    = $old->deleted_by + 0;
			$data['meta']          = '';
			$data['created_by']    = $old->created_by + 0;
			$data['updated_by']    = $old->updated_by + 0;
			$data['deleted_by']    = $old->deleted_by + 0;
			$data['published_by']  = $old->published_by + 0;
			$data['card_no']       = $old->card_no + 0;

			$data['card_no']       = ed($data['card_no']);
			$data['code_melli']    = ed($data['code_melli']);
			$data['name_first']    = pd($data['name_first']);
			$data['name_last']     = pd($data['name_last']);
			$data['name_father']   = pd($data['name_father']);
			$data['mobile']        = ed($data['mobile']);
			$data['tel_emergency'] = ed($data['tel_emergency']);
			$data['home_address']  = pd($data['home_address']);
			$data['home_postal']   = ed($data['home_postal']);
			$data['work_address']  = pd($data['work_address']);
			$data['work_postal']   = ed($data['work_postal']);


			if(!$old->birth_date or $old->birth_date == '0000-00-00' or intval($old->birth_date) > 2017) {
				$data['birth_date'] = Carbon::createFromDate(1900, 1, 1)->toDateString();
			}

			if(!User::find($data['id'])) {
				$user = User::create($data);
				$old->update([
					'converted' => "1",
				]);
				$counter++;
			}

			/*-----------------------------------------------
			| Role Attachment ...
			*/
			if($old->card_status > 0) {
				$user->attachRole('card-holder');
			}
			if($old->volunteer_status > 0) {
				if($old->domain) {
					$user->attachRole('volunteer-' . $old->domain, $old->volunteer_status);
				}
				else {
					$user->attachRole('manager', $old->volunteer_status);
				}
			}

			$last_user_id = $user->id;
		}

		ss("Counter: $counter");
		ss("Last Created id: " . $last_user_id);
		ss("Took " . strval($took = Carbon::now()->diffInSeconds($last_time)) . " Second(s) for $take Record(s). ");

		if($took > $take) {
			ss("(Average: " . strval(round($took / $take, 2)) . " Seconds per Record.) ");
		}
		else {
			ss("(Average: " . strval(round($take / $took, 2)) . " Records per Second.) ");
		}

		//return ;
		if($counter > 0 and $loop) {
			session()->put('convert_last_time', Carbon::now());
			echo "<script>location.reload();</script>";
		}
	}

	public function testRole()
	{
		$users        = User::where('converted', 0)->take(500)->get();
		$counter      = 0;
		$last_user_id = 0;

		foreach($users as $user) {
			$user->attachRole('test');
			$user->update([
				'converted' => "1",
			]);
			$last_user_id = $user->id;
			$counter++;
		}

		ss($counter);
		ss($last_user_id);
		echo "<script>location.reload();</script>";

	}

	public function statesToDomains()
	{
		$states = State::where('domain_id', '0')->get();

		foreach($states as $state) {
			$guess = Domain::where('title', $state->title)->first();
			if($guess and $guess->id) {
				$domain_id = $guess->id;
			}
			elseif($state->province->id) {
				$domain_id = $state->province->domain_id;
			}

			if(isset($domain_id)) {
				$ok = $state->update([
					'domain_id' => $domain_id,
				]);
				ss($state->title);
			}

		}

		ss('done');
	}

	public function tests()
	{
		//login(303793) ;
		ss(user()->id);
		//ss(user()->hasRole('card-holder')) ;
		ss(user()->min(0)->rolesArray());
		ss(user()->rolesQuery());

		//ss(DB::table('role_user')->where('user_id' , user()->id)->get());
		//ss(user(303793)->rolesArray()) ;

	}

	public function tests2()
	{
		return login(303793);
	}


}
