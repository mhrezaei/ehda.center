<style>
    .icon-image-wrapper {
        position: relative;
        display: inline-block;
    }

    .icon-image-wrapper img {
        width: 100%;
        height: 100%;
    }

    .icon-image-wrapper:after {
        content: "";
        font-family: FontAwesome;
        font-style: normal;
        font-weight: normal;
        text-decoration: inherit;
        position: absolute;
        font-size: {{ ($defaultSize * 0.7) }}px;
        top: 10%;
        left: 10%;
        margin: auto;
        z-index: 1;
        width: 80%;
        height: 80%;
        text-align: center;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }

    .icon-image-wrapper.video:after {
        background-image: url({{ url('assets/images/template/file-video-o.svg') }});
    }

    .icon-image-wrapper.audio:after {
        background-image: url({{ url('assets/images/template/file-audio-o.svg') }});
    }

    .icon-image-wrapper.text:after,
    .icon-image-wrapper.application:after,
    .icon-image-wrapper.docs:after {
        background-image: url({{ url('assets/images/template/file-text-o.svg') }});
    }
</style>