<div class="box">
	<article class="media">
		<div class="media-content">
			<div class="content">
				<h2>Picks - <a href="">{{ $date }}</a></h2>
				
				<div class="columns is-multiline is-mobile no-mb">
					@forelse($picks as $pick)
						<div class="column is-2-desktop is-one-quarter-tablet is-half-mobile text-center">
							<h3>{{ $pick->player->name }}</h3>
							<div class="level flex-mobile">
								<div class="level-left"></div>
								<div><img src="{{ $pick->team->logo }}" class="logo-small"/>
								<p class="vertical-middle">{{ $pick->team->name }}</p></div>
								<div class="level-right"></div>
							</div>
						</div>

					@empty
						<div class="column">
							No picks for this week have been set.
						</div>
					@endforelse
				</div>
			</div>
		</div>
	</article>
</div>