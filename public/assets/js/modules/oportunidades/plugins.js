$(document).ready(function (e) {
    "use strict";
    //Init Bootstrap Calendar Plugin
    $(':input[data-inputmask]').inputmask();
    $("#fecha_desde").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function (selectedDate) {
            $("#fecha_hasta").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#fecha_hasta").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function (selectedDate) {
            $("#fecha_desde").datepicker("option", "maxDate", selectedDate);
        }
    });

});
