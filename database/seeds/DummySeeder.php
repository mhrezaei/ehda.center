<?php

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
				'slug' => strtolower(DummyServiceProvider::englishWord()),
				'title' => DummyServiceProvider::persianWord(),
			]);
		}

		for($i=1 ; $i<=10 ; $i++) {
			\App\Models\Category::create([
				'folder_id' => rand(1,3),
				'slug' => strtolower(DummyServiceProvider::englishWord()),
				'title' => DummyServiceProvider::persianWord(),
			]);
		}
    }
}
