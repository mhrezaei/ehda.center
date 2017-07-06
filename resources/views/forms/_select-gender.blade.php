<?php
if (!isset($name)) {
    $name = 'gender';
}
?>

@include('front.forms.buttonset', [
	'options' => \Illuminate\Support\Facades\Lang::get('forms.gender'),
])
<div class="col-xs-12">
    <span class="help-block persian {{$hint_class or ''}}" style="{{$hint_style or ''}}">
        {{ $hint or '' }}
    </span>
</div>
