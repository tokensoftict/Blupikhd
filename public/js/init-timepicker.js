(function($) {

    'use strict';

    $(document).ready(function() {

        $('#timepicker1').timepicker();
        $('#timepicker_from').timepicker();
        $('#timepicker_to').timepicker();

        $('#timepicker2').timepicker({
            autoclose: true,
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false
        });

    });

})(window.jQuery);
