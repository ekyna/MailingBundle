(function(win, $, router) {

    $(win.document).ready(function() {

        var $controls = $('#execution-controls');
        var url = $controls.data('url');

        function updateControls() {
            $.get(url, function(html) {
                $controls.html($(html));
            });
        }

        if (url.length > 0) {
            setInterval(updateControls, 4000);
        }
    });

})(window, jQuery, Routing);