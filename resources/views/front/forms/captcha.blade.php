<div class="form-group">
    <label class="control-label" for="secQ">{{ $question or 'فلان به‌علاوه فلان' }}</label>
    <input name="security" type="text" class="form-control required" placeholder="{{$placeholder or ''}}">
    <input name="key" type="hidden" value="{{ $key or '' }}">
    <span id="" class="help-block">{{ $hint or '' }}</span>
</div>
