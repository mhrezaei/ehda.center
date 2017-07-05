<div class="col-xs-12 mt10 text-center">
    <a class="btn btn-blue download-btn" href="{{ user()->cards($cardTypes[0], 'download') }}"
       target="_blank">
        <i class="fa fa-download"></i>
        &nbsp;
        {{ trans('front.download') }}
    </a>
    <a class="btn btn-blue" href="{{ user()->cards('full', 'print') }}"
       target="_blank">
        <i class="fa fa-print"></i>
        &nbsp;
        {{ trans('front.print') }}
    </a>
    <a class="btn btn-blue share-btn" href="#">
        <i class="fa fa-share-alt"></i>
        &nbsp;
        {{ trans('front.share') }}
    </a>
</div>

@include('front.user.dashboard.card_part_action_buttons_sharing')