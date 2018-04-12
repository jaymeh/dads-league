@if(Session::has('message'))
	<article class="message is-medium is-danger">
		<div class="message-header">
			<p>Medium message</p>
		</div>
		<div class="message-body">
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. <strong>Pellentesque risus mi</strong>, tempus quis placerat ut, porta nec nulla.Nullam gravida purus diam, et dictum <a>felis venenatis</a> efficitur. Aenean ac <em>eleifend lacus</em>, in mollis lectus.
		</div>
	</article>
@endif