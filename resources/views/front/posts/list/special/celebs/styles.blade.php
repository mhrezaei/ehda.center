<style type="text/css">
    .ehda-card {
        display: none
    }

    ul.waterfall li {
        left: 0;
        top: 0;
        opacity: 0;
        z-index: 0;
    }

    ul.waterfall li.show {
        opacity: 1;
        transition: all 0.3s, top 1s;
    }

    ul.waterfall li > div {
        transition: all 0.5s;
    }

    /***************************************/

    .yt-gallery ul.waterfall {
        padding: 0;
    }

    .yt-gallery ul.waterfall .yt-gallery-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

</style>