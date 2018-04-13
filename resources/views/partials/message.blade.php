@if(Session::has('message'))
	<article class="message {{ Session::get('message_size') }} {{ Session::get('message_class') }}">
		@if(Session::has('message_title'))
			<div class="message-header">
				<p>{{ Session::get('message_title') }}</p>
			</div>
		@endif
		<div class="message-body">
			{{ Session::get('message') }}
		</div>
	</article>
@endif