@if(!isset($groupColor) or !$groupColor)
    @php $groupColor = 'darkGray' @endphp
@endif
@if(!isset($categoryColor) or !$categoryColor)
    @php $categoryColor = 'darkGray' @endphp
@endif
@if(!isset($titleColor) or !$titleColor)
    @php $titleColor = 'darkGray' @endphp
@endif
@if(!isset($descriptionColor) or !$descriptionColor)
    @php $descriptionColor = 'darkGray' @endphp
@endif


@if((isset($group) and $group) or (isset($title) and $title) or (isset($description) and $description))
    @if (isset($group) and  $group)
        @if(\Illuminate\Support\Facades\Lang::has('ehda.header_title.' . $group))
            @php $group = trans('ehda.header_title.' . $group) @endphp
        @endif
    @endif

    <div class="row mb20">

        @if(isset($group) and $group)
            <div class="page-{{ $groupColor }}-title col-xs-12">
                <h3 class="container">{{ $group }}</h3>
            </div>
        @endif
        <div class="col-xs-12">
            <div class="container">
                @if(isset($category) and $category)
                    <h2 class="text-{{ $categoryColor }}">{{ $category }}</h2>
                @endif
                @if(isset($title) and $title)
                    <h3 class="gallery-title sub-title text-{{ $titleColor }}">{{ $title }}</h3>
                @endif
                @if(isset($description) and $description)
                    <p class="gallery-description text-{{ $descriptionColor }}">{{ $description }}</p>
                @endif
            </div>
        </div>
    </div>
@endif