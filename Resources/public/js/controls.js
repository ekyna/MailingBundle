require(['jquery'], function($) {
    var $controls = $('#execution-controls').eq(0);
    if ($controls.length == 1) {
        var url = $controls.data('url');
        if (url.length > 0) {
            setInterval(function() {
                $.get(url, function (html) {
                    $controls.html($(html));
                });
            }, 5000);
        }
    }
});