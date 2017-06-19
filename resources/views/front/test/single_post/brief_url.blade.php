
{{-- temp if for post --}}
{{ null, $postId =17 }}

<div class="form-group" style="text-align: left;">
    <label for="short_link" style="font-size: 10px;">لینک کوتاه&nbsp;</label>
    <input id="short_link" value="{{ url('/' . /* $post->id*/ $postId) }}" style="float: left; width: 170px;" class="form-control">
</div>