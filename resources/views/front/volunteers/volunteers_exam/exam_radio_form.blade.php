<div class="radio">
    <label style="font-size: 12px;">
        <input type="radio" value="{{ $value or '' }}" id="answer-{{ $id or '' }}" name="answer-{{ $id or '' }}"
               style="display: block; font-size: 12px;">
        {{ $label or '' }})
        &nbsp;
        @pd($title)
    </label>
</div>