<?php

namespace App\Console\Commands;

use App\Models\PlayerTeam;
use App\Models\Season;
use App\Models\Table;
use Illuminate\Console\Command;

class TallyScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:tally-scores {season_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tallies up the scores for this season on a per player basis.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $season_id = $this->argument('season_id');

        if(!$season_id)
        {
            // Find current season
            $season = current_season();
        }
        else
        {
            $season = Season::whereId($season_id)->first();
        }

        if(!$season)
        {
            $this->info('No season is currently active.');
            return;
        }

        $pick_list = PlayerTeam::whereHas('fixture.game')
            ->where('season_id', $season->id)
            ->with(['player', 'fixture.game'])
            ->get()
            ->groupBy('player_id')
            ->map(function($player) {
                $point_map = $player->map(function($pick) {
                    $game_status = $pick->gameStatus;
                    $pick->status = $game_status;

                    switch($game_status)
                    {
                        case 'Win':
                            $pick->points = 3;
                            break;

                        case 'Draw':
                            $pick->points = 1;
                            break;

                        case 'Lose':
                            $pick->points = 0;
                            break;
                    }

                    return $pick;


                });

                $point_map->score = (($point_map->where('status', 'Win')->count()) * 3) + ($point_map->where('status', 'Draw')->count());

                $point_map->win_count = $point_map->where('status', 'Win')->count();
                $point_map->draw_count = $point_map->where('status', 'Draw')->count();
                $point_map->lose_count = $point_map->where('status', 'Lose')->count();

                return $point_map;
            });

        $pick_list->each(function($pick, $key) use ($season) {
            $table = Table::updateOrCreate([
                'season_id' => $season->id,
                'player_id' => $key
            ], [
                'score'     => $pick->score,
                'wins'      => $pick->win_count,
                'draws'     => $pick->draw_count,
                'losses'    => $pick->lose_count
            ]);
        });
    }
}
