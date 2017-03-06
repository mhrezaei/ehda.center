<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Providers\DummyServiceProvider ;

class DummySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		/*-----------------------------------------------
		| categories ...
		*/
		for($i=1 ; $i<=3 ; $i++) {
			\App\Models\Folder::create([
				'posttype_id' => "2",
				'slug' => "dummy-".strtolower(DummyServiceProvider::englishWord()),
				'title' => DummyServiceProvider::persianWord(),
			]);
		}

		for($i=1 ; $i<=20 ; $i++) {
			\App\Models\Category::create([
				'folder_id' => rand(1,3),
				'slug' => "dummy-".strtolower(DummyServiceProvider::englishWord()),
				'title' => DummyServiceProvider::persianWord(),
			]);
		}

		/*-----------------------------------------------
		| admins ...
		*/
		for($i=1 ; $i<=10 ; $i++) {
			\Illuminate\Foundation\Auth\User::create([
				'code_melli' => rand(1000000000 , mt_getrandmax()) ,
				'email' => DummyServiceProvider::email(),
				'name_first' => DummyServiceProvider::persianWord(),
				'name_last' => DummyServiceProvider::persianWord(),
				'password' => bcrypt('11111111'),
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			]);
		}



		/*-----------------------------------------------
		| posts ...
		*/
		for($i=1 ; $i<=20 ; $i++) {
			$deleted = !boolval(rand(0,10));
			$published = boolval(rand(0,1));
			$creator = rand(1,10);
			$price = rand(1,30) * 1000;

			\App\Models\Post::create([
				'slug' => str_slug("dummy-".strtolower(DummyServiceProvider::englishWord())),
				'type' => "products",
				'title' => DummyServiceProvider::persianTitle(),
				'locale' => array_random(['fa','ar']),
				'price' => $price,
				'is_available' => rand(0,1),
				'is_draft' => $published? 0 : rand(0,1),
				'sisterhood' => \Vinkla\Hashids\Facades\Hashids::encode( rand(5000 , 500000)),
				'meta' => json_encode([
					'text' => DummyServiceProvider::persianText( rand(1,30)),
					'abstract' => DummyServiceProvider::persianWord(rand(10,50)),
					'edu_city' => DummyServiceProvider::persianWord(),
					'home_address' => DummyServiceProvider::persianWord(5),
					'package_id' => rand(1,4),
					'sale_price' => $price * rand(60,99) / 100,
					'template' => "product",
				]),
				'deleted_at' => $deleted? Carbon::now()->toDateTimeString() : null,
				'created_by' => $creator,
				'deleted_by' => $deleted? rand(1,10):0,
				'published_at' => $published? Carbon::now()->toDateTimeString() : null,
				'published_by' => $published? 1 : 0,
				'owned_by' => boolval(rand(0,10))? $creator : rand(1,10),
				'moderated_by' => $published? 1 : rand(0,1),
				'moderated_at' => Carbon::now()->toDateTimeString(),
			]);
		}

    }
}
