$(function () {
    $(".iran-map svg g path").hover(function () {
        var t = $(this).attr("data-name"),
            l = ($(this).parent("g").attr("class"), $(".states-list ." + t + " a").html());
        l && ($(this).attr("disabled") ? $(".iran-map .show-title").html(l + " <small>(غیرفعال)</small>").css({
            display: "block"
        }) : $(".iran-map .show-title").html(l).css({
            display: "block"
        }))
    }, function () {
        $(".iran-map .show-title").html("").css({
            display: "none"
        })
    }), $(".iran-map").mousemove(function (t) {
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
