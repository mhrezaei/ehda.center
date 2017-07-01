@if(isset($showSideBar) and
    $showSideBar and
    isset($sideBarItems) and
    $sideBarItems and
    is_array($sideBarItems)
)
    <div class="sidebar col-xs-12 col-md-4">
        <div class="widget">
            <h4 class="widget-title text-success">سایر خبرها</h4>
            <div class="widget-content">
                <ul class="list-unstyled">
                    @foreach($sideBarItems as $item)
                        <li><a href="{{ $item['link'] }}">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif