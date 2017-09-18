<div class="page attachments-browser" id="gallery">
    <div id="filters" class="media-toolbar">
        <div class="toolbar-right col-xs-6">
            <div class="row">
                <div class="col-xs-6 pull-start">
                    <select id="filter-file-type" class="attachment-filters full-width" name="fileType">
                        <option value="">{{ trans('file-manager.type-all') }}</option>
                        <option value="image">{{ trans('front.file_types.image.title') }}</option>
                        <option value="audio">{{ trans('front.file_types.audio.title') }}</option>
                        <option value="video">{{ trans('front.file_types.video.title') }}</option>
                        <option value="text">{{ trans('front.file_types.text.title') }}</option>
                        <option value="compressed">{{ trans('front.file_types.compressed.title') }}</option>
                    </select>
                </div>
                <div class="col-xs-6 pull-start">
                    <select class="attachment-filters full-width" name="sort">
                        <option value="time.desc" selected>
                            {{ trans('file-manager.sort-time') }} - {{ trans('file-manager.sort-descending') }}
                        </option>
                        <option value="time.asc">
                            {{ trans('file-manager.sort-time') }} - {{ trans('file-manager.sort-ascending') }}
                        </option>
                        <option value="name.asc">
                            {{ trans('file-manager.sort-name') }} - {{ trans('file-manager.sort-ascending') }}
                        </option>
                        <option value="name.desc">
                            {{ trans('file-manager.sort-name') }} - {{ trans('file-manager.sort-descending') }}
                        </option>
                        <option value="size.asc">
                            {{ trans('file-manager.sort-size') }} - {{ trans('file-manager.sort-ascending') }}
                        </option>
                        <option value="size.desc">
                            {{ trans('file-manager.sort-size') }} - {{ trans('file-manager.sort-descending') }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="toolbar-left col-xs-6">
            <div class="row">
                <div class="col-xs-9 pull-start">
                    <input typle="search" placeholder="جستجو در رسانه ها" class="search full-width" name="search"/>
                </div>
                <div class="col-xs-3 pull-start">
                    <button type="button" class="refresh" onclick="refreshGallery()"><i class="fa fa-refresh"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="file-list-view">
        <div id="loading-dialog" class="loading-dialog text-center text-blue pt30" style="display: none">
            <p>
                {{ trans('forms.feed.wait') }}
            </p>
            <img src="{{ asset('assets/images/template/AjaxLoader.gif') }}">
        </div>
        <div class="thumbnail-container">
            <ul class="" id="thumbnail">
                @include('file-manager.media-frame-content-gallery-images-list')
            </ul>
        </div>
    </div>

    <div class="media-sidebar">
        <div id="sidebar-loading" class="sidebar-loading" style="display: none;">
            <img src="{{ asset('assets/images/template/AjaxLoader.gif') }}">
        </div>

        <div class="close-sidebar">
            <span class="fa fa-chevron-left"></span>
        </div>
        <div class="media-uploader-status" style="display: none">
            <h2>در حال بارگذاری...</h2>
            <button type="button" class="fa fa-times-circle upload-dismiss"></button>
            <div class="media-progress">
                <div></div>
            </div>
            <div class="upload-detail">
                <span class="upload-count">
                    <span class="upload-index">1</span>
                    /
                    <span class="upload-total">1</span>
                </span>
                <span class="upload-detail-separator">_</span>
                <span class="upload-filename">blabla.jpg</span>
            </div>
            <span class="upload-errors"></span>
        </div>
        <div class="file-details pt10"></div>
    </div>

</div>