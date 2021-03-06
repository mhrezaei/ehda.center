! function (e) {
    "use strict";

    function t(n, i) {
        if (!(this instanceof t)) {
            var a = new t(e.extend({
                $source: n,
                $currentTarget: n.first()
            }, i));
            return a.open(), a
        }
        e.featherlight.apply(this, arguments), this.chainCallbacks(o)
    }
    var n = function (e) {
        window.console && window.console.warn && window.console.warn("FeatherlightGallery: " + e)
    };
    if ("undefined" == typeof e) return n("Too much lightness, Featherlight needs jQuery.");
    if (!e.featherlight) return n("Load the featherlight plugin before the gallery plugin");
    var i = "ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch,
        a = e.event && e.event.special.swipeleft && e,
        r = window.Hammer && function (e) {
                var t = new window.Hammer.Manager(e[0]);
                return t.add(new window.Hammer.Swipe), t
            },
        s = i && (a || r);
    i && !s && n("No compatible swipe library detected; one must be included before featherlightGallery for swipe motions to navigate the galleries.");
    var o = {
        afterClose: function (e, t) {
            var n = this;
            return n.$instance.off("next." + n.namespace + " previous." + n.namespace), n._swiper && (n._swiper.off("swipeleft", n._swipeleft).off("swiperight", n._swiperight), n._swiper = null), e(t)
        },
        beforeOpen: function (e, t) {
            var n = this;
            return n.$instance.on("next." + n.namespace + " previous." + n.namespace, function (e) {
                var t = "next" === e.type ? 1 : -1;
                n.navigateTo(n.currentNavigation() + t)
            }), s ? n._swiper = s(n.$instance).on("swipeleft", n._swipeleft = function () {
                n.$instance.trigger("next")
            }).on("swiperight", n._swiperight = function () {
                n.$instance.trigger("previous")
            }) : n.$instance.find("." + n.namespace + "-content").append(n.createNavigation("previous")).append(n.createNavigation("next")), e(t)
        },
        beforeContent: function (e, t) {
            var n = this.currentNavigation(),
                i = this.slides().length;
            return this.$instance.toggleClass(this.namespace + "-first-slide", 0 === n).toggleClass(this.namespace + "-last-slide", n === i - 1), e(t)
        },
        onKeyUp: function (e, t) {
            var n = {
                37: "previous",
                39: "next"
            }[t.keyCode];
            return n ? (this.$instance.trigger(n), !1) : e(t)
        }
    };
    e.featherlight.extend(t, {
        autoBind: "[data-featherlight-gallery]"
    }), e.extend(t.prototype, {
        previousIcon: "&#9664;",
        nextIcon: "&#9654;",
        galleryFadeIn: 100,
        galleryFadeOut: 300,
        slides: function () {
            return this.filter ? this.$source.find(this.filter) : this.$source
        },
        images: function () {
            return n("images is deprecated, please use slides instead"), this.slides()
        },
        currentNavigation: function () {
            return this.slides().index(this.$currentTarget)
        },
        navigateTo: function (t) {
            var n = this,
                i = n.slides(),
                a = i.length,
                r = n.$instance.find("." + n.namespace + "-inner");
            return t = (t % a + a) % a, n.$currentTarget = i.eq(t), n.beforeContent(), e.when(n.getContent(), r.fadeTo(n.galleryFadeOut, .2)).always(function (e) {
                n.setContent(e), n.afterContent(), e.fadeTo(n.galleryFadeIn, 1)
            })
        },
        createNavigation: function (t) {
            var n = this;
            return e('<span title="' + t + '" class="' + this.namespace + "-" + t + '"><span>' + this[t + "Icon"] + "</span></span>").click(function () {
                e(this).trigger(t + "." + n.namespace)
            })
        }
    }), e.featherlightGallery = t, e.fn.featherlightGallery = function (e) {
        return t.attach(this, e)
    }, e(document).ready(function () {
        t._onReady()
    })
}(jQuery);