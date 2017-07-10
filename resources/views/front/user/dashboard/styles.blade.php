<style>
    .tab-content .tab-pane img {
        height: 300px;
    }

    .tab-content .tab-pane {
        padding: 10px 15px;
    }

    .tab-content .tab-pane img {
        object-fit: contain;
        margin: auto;
        padding: 10px;
    }

    .tab-content .tab-pane a {
        display: inline-block;
        position: relative;
    }

    .tab-content .tab-pane a button.expand-btn {
        position: absolute;
        bottom: 0;
        left: 0 ;
        padding: 15px 20px 10px 15px;
        border-radius: 0 90% 0 0;
        opacity: 0;
        transition: all 0.4s;
    }

    .tab-content .tab-pane a:hover button.expand-btn {
        opacity: .8;
    }

    .a2a_kit {
        display: flex;
        justify-content: center;
    }
</style>