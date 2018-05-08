<?php

use Illuminate\Database\Seeder;

class FixturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Here sould probably be where I do validation.

        factory(App\Models\Fixture::class)->create();
    }
}
