<div class="page attachments-browser" id="gallery">
    <div class="media-toolbar">
        <div class="toolbar-right">
            <select id="media-filters" class="attachment-filters">
                <option value="all">همه‌ی موارد رسانه‌ای</option>
                <option value="uploaded">بارگذاری شده در این نوشته</option>
                <option value="image">تصویرها</option>
                <option value="audio">صوت</option>
                <option value="video">ویدیو</option>
                <option value="unattached">پیوست‌نشده</option>
            </select>
            <select id="date-filters" class="attachment-filters">
                <option value="all">همه تاریخ ها</option>
                <option value="uploaded">بارگذاری شده در این نوشته</option>
                <option value="image">تصویرها</option>
                <option value="audio">صوت</option>
                <option value="video">ویدیو</option>
                <option value="unattached">پیوست‌نشده</option>
            </select>
        </div>
        <div class="toolbar-left">
            <button type="button" onclick="refreshGallery()"><i class="fa fa-refresh"></i></button>
            <input typle="search" placeholder="جستجو در رسانه ها" class="search"></input>
        </div>
    </div>
    <ul class="thumbnail-container row" id="thumbnail">
        @include('file-manager.media-frame-content-gallery-images-list')
    </ul>
    <div class="media-sidebar">
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
        <div class="file-details"></div>
    </div>

</div>