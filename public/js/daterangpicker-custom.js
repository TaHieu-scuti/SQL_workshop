
$(function() {
    $('#img-datefilter').on('click', function() {
        $('#datefilter').click();
    });
    let lang = $('html').attr('lang');
    $('#datefilter').dateRangePicker({
        autoClose: false,
        format: 'YYYY-MM-DD',
        separator: ' - ',
        language: lang,
        startOfWeek: 'sunday',// or monday
        getValue: function()
        {
            return $(this).val();
        },
        setValue: function(s)
        {
            if(!$(this).attr('readonly') && !$(this).is(':disabled') && s != $(this).val())
            {
                $(this).val(s);
            }
        },
        startDate: false,
        endDate: false,
        time: {
            enabled: false
        },
        minDays: 0,
        maxDays: 0,
        showShortcuts: false,
        shortcuts:
            {
            },
        customShortcuts : [],
        inline:false,
        container:'body',
        alwaysOpen:false,
        singleDate:false,
        lookBehind: false,
        batchMode: false,
        duration: 200,
        stickyMonths: false,
        dayDivAttrs: [],
        dayTdAttrs: [],
        applyBtnClass: 'cancel',
        singleMonth: 'auto',
        hoveringTooltip: function(days, startTime, hoveringTime)
            {
                return days > 1 ? days + ' ' : '';
            },
        showTopbar: true,
        swapTime: false,
        selectForward: false,
        selectBackward: false,
         showWeekNumbers: false,
        getWeekNumber: function(date) //date will be the first day of a week
            {
            return moment(date).format('w');
        },
        monthSelect: true,
        yearSelect:  [1900, moment().get('year')]
    });
});
