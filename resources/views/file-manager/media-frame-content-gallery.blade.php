<div class="page attachments-browser" id="gallery" style="display: none;">
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
            <input typle="search" placeholder="جستجو در رسانه ها" class="search"></input>
        </div>
    </div>
    @include('file-manager.media-frame-content-gallery-images-list')
    <div class="media-sidebar">
        <div class="close-sidebar">
            <span class="fa fa-chevron-left"></span>
        </div>
        <div class="media-uploader-status">
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
        <div class="file-details">
            <h2>جزئیات پیوست</h2>
            <div class="attachment-info">
                <div class="thumbnail-image">
                    <img src="img/7_8_oclock-300x152.jpg">
                </div>
                <div class="details">
                    <div class="filename">portrait.jfif</div>
                    <div class="upload-date">15 مرداد 95</div>
                    <div class="upload-size">400kb</div>
                    <div class="dimension">5426 × 3053</div>
                    <a href="#" class="edit-attachment">ویرایش تصویر</a>
                    <button type="button" class="delete-btn btn-link">پاک کردن برای همیشه</button>
                </div>
            </div>
            <label class="setting">
                <span class="name">نام</span>
                <input type="text"></input>
            </label>
            <label class="setting">
                <span class="name">متن جایگزین</span>
                <input type="text"></input>
            </label>
            <label class="setting">
                <span class="name">توضیح</span>
                <textarea></textarea>
            </label>
        </div>
    </div>

</div>