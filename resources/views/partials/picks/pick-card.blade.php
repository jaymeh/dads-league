<div class="card">
    <div class="card-image text-center">
        @php 
            // TODO: Fix issue with image 
        @endphp
        <player-picked-team-image :player-id="{{ $player->id }}" team-id="{{ $active_pick ? $active_pick->team_id : '' }}"></player-picked-team-image>
        }
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
                    team-id="{{ $active_pick ? $active_pick->team_id : '' }}"
                    message-error="{{ ($errors->any()) ? $errors->first('players.' . $player->id) : "" }}"></team-picker>
            </div>
        @endif
    </div>
</div>