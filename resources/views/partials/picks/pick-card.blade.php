<div class="card">
    <div class="card-image text-center">
        <player-picked-team-image :player-id="{{ $player->id }}"></player-picked-team-image>
    </div>
    <div class="card-content no-top-padding">
        <div class="media">
            <div class="media-content text-center">
                <p class="title is-4">{{ $player->name }}</p>
            </div>
        </div>

        @if($player->picks)
            <div class="content">
                <team-picker 
                    :player-id="{{ $player->id }}"
                    team-id="{{ isset($player_existing_picks[$player->id]) ? $player_existing_picks[$player->id] : "" }}"
                    message-error="{{ ($errors->any()) ? $errors->first('players.' . $player->id) : "" }}"></team-picker>
            </div>
        @endif
    </div>
</div>