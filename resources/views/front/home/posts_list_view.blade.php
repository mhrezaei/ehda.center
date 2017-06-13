@if(!isset($color) or !$color)
    {{ null, $color = "black" }} {{-- default color --}}
@endif

{{ null, $showMoreColorClass = "link-$color" }}
{{ null, $itemsColorClass = "border-start-$color" }}

@if(isset($title) and $title)
    @include('front.frame.underlined_heading', [
        'text' => $title,
        'color' => $color
    ])
@endif
<a href="#" class="floating-top-20 floating-end-15 {{ $showMoreColorClass }}">بیشتر</a>
<div class="media-list">
    @for($i = 0; $i < 6; $i++)
        <div class="media-list-item {{ $itemsColorClass }}">
            <a href="#">
                <div class="media-list-item-image">
                    <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+PCEtLQpTb3VyY2UgVVJMOiBob2xkZXIuanMvNjR4NjQKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNWJhZDg3YjRhNSB0ZXh0IHsgZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE1YmFkODdiNGE1Ij48cmVjdCB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSIxNCIgeT0iMzYuNSI+NjR4NjQ8L3RleHQ+PC9nPjwvZz48L3N2Zz4="
                         class="media-object">
                </div>
                <div class="media-list-item-body">
                    <h5 class="media-list-item-heading">
                        آمار بالای نارسایی کلیه در کشور/ دستگاه‌های دیالیز فرسوده‌اند
                    </h5>
                    <p class="text-gray text-end">۲۵ اسفند ۹۴</p>
                </div>
            </a>
        </div>
    @endfor
</div>