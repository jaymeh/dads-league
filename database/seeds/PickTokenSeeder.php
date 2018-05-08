<?php

use Illuminate\Database\Seeder;

class PickTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\PickToken::class)->create();
    }
}
