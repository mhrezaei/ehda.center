@if(!isset($twoColumns))
    @php $twoColumns = false @endphp
@endif

<div class="row archive">
    @foreach($posts as $post)
        @include($viewFolder . '.item')
    @endforeach
</div>
<div class="row pagination-wrapper mt20 text-center">
    {!! $posts->render() !!}
</div>
