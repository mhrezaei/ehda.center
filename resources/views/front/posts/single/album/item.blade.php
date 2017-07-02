<div class="flex-item">
    <a @if($item['src']) href="{{ url($item['src']) }}" @endif class="inner">
        <img src="{{ url($item['src']) }}" alt="{{ $item['label'] }}">
    </a>
</div>