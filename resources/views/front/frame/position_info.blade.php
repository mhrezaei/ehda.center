@if(!isset($groupColor) or !$groupColor)
    {{ null, $groupColor = 'darkGray' }}
@endif
@if(!isset($categoryColor) or !$categoryColor)
    {{ null, $categoryColor = 'darkGray' }}
@endif
@if(!isset($titleColor) or !$titleColor)
    {{ null, $titleColor = 'darkGray' }}
@endif
@if(!isset($descriptionColor) or !$descriptionColor)
    {{ null, $descriptionColor = 'darkGray' }}
@endif

@if((isset($group) and $group) or (isset($title) and $title) or (isset($description) and $description))

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