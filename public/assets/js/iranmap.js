$(function () {
    $(".iran-map svg g.province path").hover(function () {
        let that = $(this);
        let n = that.data('persianName');
        let titleContainer = $(".iran-map .show-title");
        if (n) {
            if (that.attr("disabled")) {
                titleContainer.html(n + " <small>(غیرفعال)</small>");
            } else {
                titleContainer.html(n);
            }

            titleContainer.show();
        } else {
            titleContainer.hide();
        }
        // l && ( ? $(".iran-map .show-title").html(l + " <small>(غیرفعال)</small>").css({
        //     display: "block"
        // }) : $(".iran-map .show-title").html(l).css({
        //     display: "block"
        // }));
    }, function () {
        $(".iran-map .show-title").html("").css({
            display: "none"
        })
    });
    $(".iran-map").mousemove(function (t) {
        var l = 0,
            a = 0;
        if (!t) var t = window.event;
        if (t.pageX || t.pageY ? (l = t.pageX, a = t.pageY) : (t.clientX || t.clientY) && (l = t.clientX + document.body.scrollLeft + document.documentElement.scrollLeft, a = t.clientY + document.body.scrollTop + document.documentElement.scrollTop), $(".iran-map .show-title").html()) {
            var e = $(this).offset(),
                s = l - e.left + 25 + "px",
                o = a - e.top - 5 + "px";
            $(".iran-map .show-title").css({
                left: s,
                top: o
            })
        }
    });
})
