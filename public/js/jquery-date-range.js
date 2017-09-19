$(function() {
    $('#from').prop('disabled', true);
    $('#to').prop('disabled', true);
    var dates = $( "#from, #to" ).datepicker({
        showOn: 'button',
        buttonImage: '../images/calendar.png',
        buttonImageOnly: true,
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
});
