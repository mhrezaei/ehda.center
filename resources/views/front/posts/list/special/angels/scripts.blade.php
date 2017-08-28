@section('endOfBody')
    @foreach($posts as $post)
        @php
            $post->spreadMeta();
            $jsAngles[] = [
                    'id' => $post->id,
                    'name' => $post->title,
                    'picture_url' => $post->viewable_featured_image,
                    'donation_date' => ad(echoDate($post->donation_date, 'j F Y')),
                ]
        @endphp
    @endforeach


    {!! Html::script ('assets/libs/jquery-ui/jquery-ui.min.js') !!}
    <script>
        var searchUrl = "{{ route_locale('angels.find') }}";
        var angels = {!! json_encode($jsAngles) !!};
        $(document).ready(function () {
            random_angles(angels);
        })
    </script>
    {!! Html::script ('assets/js/angels.js') !!}
@append