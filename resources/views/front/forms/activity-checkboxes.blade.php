{{ null, $activities = model('Activity')::all() }}
@if(isset($currentValues))
    {{ null, $currentActivities = explode(',', $currentValues['activities']) }}
@else
    {{ null, $currentActivities = [] }}
@endif

@if($activities)
    @foreach($activities as $act)
        <div class="checkbox">
            <label>
                <input name="activity[]" class="volunteer_activity" type="checkbox" value="{{ $act->slug }}"
                       style="display: block;" @if(in_array($act->slug, $currentActivities)) checked="checked" @endif>
                {{ $act->title }}
            </label>
        </div>
    @endforeach
@endif