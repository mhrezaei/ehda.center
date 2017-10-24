@if($equationPost and $equationPost->exists)
    <div class="row fixed-background" style="background-image:url({{ url('assets/images/image-1.jpg') }})">
        <h3 class="text-white text-center" style="line-height:3em;font-size:450%;height:3em; direction: rtl">{{ ad($equationPost->title) }}</h3>
    </div>
@endif