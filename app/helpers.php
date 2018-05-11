<?php

if(!function_exists('trigger_message'))
{
	/**
	 * @param  string
	 * @param  string
	 */
	function trigger_message($message, $status = 'success', $size = 'normal', $title = '')
	{
		$message_statuses = [
			'success' => 'is-success',
			'warning' => 'is-warning',
			'error' => 'is-danger'
		];

		if(!isset($message_statuses[$status]))
		{
			throw new App\Exceptions\TriggerMessageException('Couldn\'t find status ' . $status . '. Please use one of the following: success, warning, error.');
		}

		$sizes = [
			'small' => 'is-small',
			'normal' => '',
			'medium' => 'is-medium',
			'large' => 'is-large'
		];

		if(!isset($sizes[$size]))
		{
			throw new App\Exceptions\TriggerMessageException('Couldn\'t find valid size ' . $size . ' for message. Please use one of the following: small, normal, medium, large.');
		}

		request()->session()->flash('message', $message);
		request()->session()->flash('message_class', $message_statuses[$status]);
		request()->session()->flash('message_size', $sizes[$size]);

		if($title)
		{
			request()->session()->flash('message_title', $title);
		}
	}
}

if(!function_exists('current_season')) {
	function current_season($hard_check = false) 
	{
		$date = Carbon\Carbon::now();

		$season = App\Models\Season::whereDate('start_date', '<=', $date)
			->whereDate('end_date', '>=', $date)
			->first();

		if(!$season && !$hard_check)
		{
			$season = App\Models\Season::where(function($q) use($date) {
					$q->whereDate('start_date', '<=', $date);
					$q->whereDate('end_date', '>=', $date);
				})
				->orWhere(function($q) use ($date) {
					$q->whereDate('end_date', '<', $date);
					$q->orderByDesc('end_date');
				})
				->first();
		}

		return $season;
	}
}

if(!function_exists('current_week')) {
	function week_number($date = null)
	{
		if(!$date)
		{
			$date = Carbon\Carbon::now();
		}

		$one_week = now()->addWeek();

		$season = Cache::remember('week_season_' . $date->format('Y-m-d'), $one_week, function () use ($date) {
		    return App\Models\Season::where(function($q) use($date) {
					$q->whereDate('start_date', '<=', $date);
					$q->whereDate('end_date', '>=', $date);
				})
				->orWhere(function($q) use ($date) {
					$q->whereDate('end_date', '<', $date);
					$q->orderByDesc('end_date');
				})
				->first();
		});

		$week_number = $date->diffInWeeks($season->start_date);

		return $week_number + 1;
	}
}

if(!function_exists('next_game_date'))
{
	function next_game_date($with_count = false)
	{
		$season = current_season();

		$team_picks = App\Models\PlayerTeam::select('game_date')
            ->where('season_id', $season->id)
            ->orderByDesc('game_date');

		// Find next game date
		if($with_count)
		{
			$team_picks = $team_picks
	            ->groupBy('game_date')
	            ->get();


		}
		else
		{
			$team_pick = $team_picks
				->first()
				->game_date
				->modify('+1 week');
		}

        if(!$team_picks->count())
        {
        	return false;
        }

        if($with_count)
        {
        	return [
        		'game_date' => $team_picks->first()->game_date->modify('+1 week'),
        		'week_count' => $team_picks->count()
        	];
        }

        return $team_pick;
	}
}