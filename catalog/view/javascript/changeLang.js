$(function() {
    $("a#en").on("click", function() {
        window.funLazyLoad.start(), window.funLazyLoad.show();
        var a = $(this).data("link");
        $.ajax({
            url: a,
            type: "GET",
            data: {
                lang: "english"
            },
            async: !0,
            success: function(a) {
                location.reload(!0)
            }
        })
    }), $("a#vn").on("click", function() {
        window.funLazyLoad.start(), window.funLazyLoad.show();
        var a = $(this).data("link");
        $.ajax({
            url: a,
            type: "GET",
            data: {
                lang: "vietnamese"
            },
            async: !0,
            success: function(a) {
                location.reload(!0)
            }
        })
    })
});