<div class="card">
    <div class="card-image text-center">
        <player-picked-team-image :player-id="{{ $player->id }}"></player-picked-team-image>
    </div>
<div class="card-content">
    <div class="media">
        <div class="media-content text-center">
            <p class="title is-4">{{ $player->name }}</p>
        </div>
    </div>

    @if($player->picks)
        <div class="content">
            <team-picker :player-id="{{ $player->id }}"></team-picker>
        </div>
    @endif
</div>