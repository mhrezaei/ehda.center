@if(isset($files))
    @foreach($files as $file)
        @if($file->can('preview'))
            <li class=" col-xs-6 col-sm-6 col-md-4 col-lg-3 ">
                <div class="thumbnail attachment" data-url="{{ url($file->pathname) }}"
                     data-file="{{ $file->hashid }}"
                     data-pathname="{{ $file->pathname }}">
                    {{--<img src="img/insurance-gambling-300x144.jpg" alt="">--}}
                    {!! \App\Providers\UploadServiceProvider::getFileView($file, 'thumbnail') !!}
                </div>
            </li>
        @endif
    @endforeach
@endif
{{--<li class=" col-xs-6 col-sm-6 col-md-4 col-lg-3 ">--}}
{{--<div class="thumbnail attachment">--}}
{{--<img src="img/insurance-gambling-300x144.jpg" alt="">--}}
{{--</div>--}}
{{--</li>--}}
{{--<li class=" col-xs-6 col-sm-6 col-md-4 col-lg-3 ">--}}
{{--<div class="thumbnail attachment">--}}
{{--<img src="img/7_8_oclock-300x152.jpg" alt="">--}}
{{--</div>--}}
{{--</li>--}}
{{--<li class=" col-xs-6 col-sm-6 col-md-4 col-lg-3 ">--}}
{{--<div class="thumbnail attachment">--}}
{{--<img src="img/portrait.jfif" alt="">--}}
{{--</div>--}}
{{--</li>--}}
{{--<li class=" col-xs-6 col-sm-6 col-md-4 col-lg-3 ">--}}
{{--<div class="thumbnail attachment">--}}
{{--<img src="img/insurance-gambling-300x144.jpg" alt="">--}}
{{--</div>--}}
{{--</li>--}}
{{--<li class=" col-xs-6 col-sm-6 col-md-4 col-lg-3 ">--}}
{{--<div class="thumbnail attachment">--}}
{{--<img src="img/insurance-gambling-300x144.jpg" alt="">--}}
{{--</div>--}}
{{--</li>--}}
{{--<li class=" col-xs-6 col-sm-6 col-md-4 col-lg-3 ">--}}
{{--<div class="thumbnail attachment">--}}
{{--<img src="img/insurance-gambling-300x144.jpg" alt="">--}}
{{--</div>--}}
{{--</li>--}}
{{--<li class=" col-xs-6 col-sm-6 col-md-4 col-lg-3 ">--}}
{{--<div class="thumbnail attachment">--}}
{{--<img src="img/insurance-gambling-300x144.jpg" alt="">--}}
{{--</div>--}}
{{--</li>--}}
{{--<li class=" col-xs-6 col-sm-6 col-md-4 col-lg-3 ">--}}
{{--<div class="thumbnail attachment">--}}
{{--<img src="img/insurance-gambling-300x144.jpg" alt="">--}}
{{--</div>--}}
{{--</li>--}}
{{--<li class=" col-xs-6 col-sm-6 col-md-4 col-lg-3 ">--}}
{{--<div class="thumbnail attachment">--}}
{{--<img src="img/insurance-gambling-300x144.jpg" alt="">--}}
{{--</div>--}}
{{--</li>--}}