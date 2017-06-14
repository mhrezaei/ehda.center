<div class="media @if($twoColumns) col-xs-6 @else col-xs-12 @endif">
    <a class="link-black" href="#">
        <div class="media-start">
            <img src="{{ url('photos/shares/login-background.jpg') }}">
        </div>
        <div class="media-body">
            <h4 class="media-heading">آمار بالای نارسایی کلیه در کشور</h4>
            <p class="text-justify">
                دوست گرامی، ارائه اطلاعات صحیح به دیگران درزمینه مبحث مقدس اهدای عضو از افراد مرگ
                مغزی
                می‌تواند باعث اشاعه این فرهنگ، بالا بردن اطلاعات جامعه، افزایش آمار رضایت
                خانواده‌های
                افراد مرگ مغزی به اهدای اعضای عزیزانشان و درنتیجه نجات جان انسان‌های بیشتری
                گردد.
            </p>
            <p class="text-gray">{{ echoDate(\Carbon\Carbon::now()) }}</p>
        </div>
    </a>
</div>
