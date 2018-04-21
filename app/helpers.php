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
	function current_season() 
	{
		$date = Carbon\Carbon::now();
		$current_season = App\Models\Season::whereDate('start_date', '<=', $date)
			->whereDate('end_date', '>=', $date)
			->first();

		return $current_season;
	}
}