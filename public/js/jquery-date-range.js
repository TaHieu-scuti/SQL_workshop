$(function() {
    $('#from').prop('disabled', true);
    $('#to').prop('disabled', true);
    var dates = $( "#from, #to" ).datepicker({
        buttonText: "Show calendar",
        defaultDate: "+1w",
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        onSelect: function( selectedDate ) {
            var option = this.id == "from" ? "minDate" : "maxDate",
                instance = $( this ).data( "datepicker" ),
                date = $.datepicker.parseDate(
                        instance.settings.dateFormat ||
                        $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings
                    );
            dates.not( this ).datepicker( "option", option, date );
        }
    });
    $('#ui-datepicker-div').click(function() {
        $('.date-option').addClass('activeBlock');
    });

    $('.dpd1-from').click(function() {
        $('#from').datepicker('show');
    });
    $('.dpd2-to').click(function() {
        $('#to').datepicker('show');
    });
});
