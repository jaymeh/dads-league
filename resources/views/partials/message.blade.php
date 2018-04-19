@if(Session::has('message'))
	<message 
		message-size="{{ Session::has('message_size') ? Session::get('message_size') : '' }}"
		message-class="{{ Session::has('message_class') ? Session::get('message_class') : '' }}"
		message-title="{{ Session::has('message_title') ? Session::get('message_title') : '' }}"
		message="{{ Session::get('message') }}">
	</message>
@endif