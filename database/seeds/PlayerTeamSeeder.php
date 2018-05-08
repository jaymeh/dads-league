<?php

use Illuminate\Database\Seeder;

class PlayerTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\PlayerTeam::class)->create();
    }
}
