{!! Form::open([
    'url'	=> '/register/register_third_step' ,
    'method'=> 'post',
    'class' => 'clearfix ehda-card-form js',
    'name' => 'register_third_step',
    'id' => 'register_third_step',
    'style' => 'display: none;'
]) !!}

@include('forms.feed')

<div class="form-group text-center">
    <button type="submit" class="btn btn-link submit">
        <img src="{{ url('') }}/assets/site/images/form-submit.png" width="190" height="190">
    </button>
    <div style="clear: both; margin-top: 15px;"></div>
    <input type="hidden" name="db-check" id="db-check" value="null">
    <button type="button" class="btn btn-db-check">{{ trans('forms.button.oh_no') }}</button>
</div>

{!! Form::close() !!}