<div class="container new-angel-form-container" style="height: 0">
    <div class="col-xs-12 align-horizontal-center new-angel-form-container-inner">
        <div class="col-xs-12 col-md-8 col-sm-10">
            <div class="col-xs-12">
                @include('front.frame.underlined_heading', [
                    'text' => trans('front.angels.new'),
                    'color' => 'blue'
                ])
            </div>
            {!! \App\Providers\PostsServiceProvider::showPost('new-angel', [
                'variables' => [
                    'fileTypePrefix' => 'client.angels',
                ]
            ]) !!}
        </div>
    </div>
</div>
