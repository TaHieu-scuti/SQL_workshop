var link = window.location.pathname;
var prefixRoute = getRoutePrefix();

$('.selectpicker').selectpicker();
/*
=======
* stop drop-down menu form disappearing on clicking
*/
$('.dropdown-menu.tasks-bar input').click(function (event) {
    event.stopPropagation();
});

$('.dropdown-menu.date-option .custom-li').click(function () {
    $('.dropdown-menu.date-option').click(function (event) {
        event.stopPropagation();
    });
});
/*
*
* onclicking effect of custom date picker
* hide dropdown-menu excluding custom row
* show calendar for date picking
* adjust height of ul of time period selection
*/
$('.dropdown-menu .custom-li').click(function () {
    $('.dropdown-divider').hide();
    $('.selected-time .dropdown-menu.extended.tasks-bar li').not('.custom-li').hide();
    $('.custom-date').show();
    $('.selected-time .dropdown-menu.extended.tasks-bar').css('height', 'auto');
});
/*
*
* onclicking effect of cancel custom date picker
* show dropdown-menu excluding custom row
* hide calendar for date picking
* adjust height of ul of time period selection
*/
$('.btn-danger').click(function () {
    $('.dropdown-divider').show();
    $('.selected-time .dropdown-menu.extended.tasks-bar li').not('.custom-li').show();
    $('.custom-date').hide();
    $('.selected-time .dropdown-menu.extended.tasks-bar').css('height', '294px');
});

/*
*
* display no data found message on table if no data found
*/
function processDataTable(response) {
    if(response.displayNoDataFoundMessageOnTable) {
        $('.no-data-found-table.hidden-no-data-found-message-table')
            .removeClass('hidden-no-data-found-message-table');
    } else {
        $('.no-data-found-table')
            .addClass('hidden-no-data-found-message-table');
    }
}
$(window).on('hashchange', function() {
    if (window.location.hash) {
        var page = window.location.hash.replace('#', '');
        if (page == Number.NaN || page <= 0) {
            return false;
        } else {
            getAccountReports(page);
        }
    }
});

function sendingRequestTable() {
    $('.loading-gif-on-table').removeClass('hidden-table');
    setTimeout(function() {
        $('.loading-gif-on-table').show();
    }, 10);
}

function completeRequestTable()
{
    $('.loading-gif-on-table').addClass('hidden-table');
}
/*
*
* onclicking apply button
* display selected columns
* update table with selected columns
*/
$(".apply-button").click(function () {
    var array = [];
    if (!array['fieldName']) {
        array['fieldName'] = [];
    }

    if(!array['paginaton']) {
        array['pagination'] = $("input[name='resultPerPage']:checked").val();
    }

    $.each($("input[name='fieldName']:checked"), function () {
        array['fieldName'].push($(this).val());
    });
    $.ajax({
        url: prefixRoute + "/update-table",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            'pagination' : array['pagination'],
            'fieldName' : array['fieldName'],
        },
        beforeSend : function () {
            setTimeout(function() {
                $('#columnsModal').modal('hide');
            }, 1);
            setTimeout(function() {
                sendingRequestTable();
            }, 200);
        },
        success: function(response) {
            $('.table_data_report').html(response.tableDataLayout);
            $('.summary_report').html(response.summaryReportLayout);
            processDataTable(response);
            history.pushState("", "", link);
        },
        error : function (response) {
            alert('Something went wrong!');
        },
        complete : function () {
            completeRequestTable();
        }
    });
});

$('input[name="fieldName"]:checkbox').change(function() {
    filterColumnChecked();
});

function filterColumnChecked() {
    var array= [];
    $.each($("input[name='fieldName']:checked"), function() {
        array.push($(this).val());
    });
    if (array.length === 1) {
        $("input[name='fieldName']:checked").attr("disabled", true);
    } else if(array.length > 1) {
        $("input[name='fieldName']:checkbox").removeAttr('disabled');
    }
}
filterColumnChecked();
/*
*
* onclicking date button
* update table with selected time period
*/
$('.date-option li:not(.custom-li, .custom-date)').click(function () {
    var option = $(this).data('date');
    var milestone = getFilterDate(option);
    $.ajax({
        url : prefixRoute + "/update-table",
        type : "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            'startDay' : milestone['startDay'],
            'endDay' : milestone['endDay'],
            'timePeriodTitle' : milestone['timePeriodTitle'],
        },
        beforeSend : function () {
            sendingRequestTable();
        },
        success : function (response) {
            $('.table_data_report').html(response.tableDataLayout);
            $('.summary_report').html(response.summaryReportLayout);
            processDataTable(response);
            history.pushState("", "", link);
        },
        error : function (response) {
            alert('Something went wrong!');
        },
        complete : function () {
            completeRequestTable();
        }
    });
});

$('.apply-custom-period').click(function() {
    var option = $('.custom-li').data('date');
    var startDay = $('.dpd1').val();
    var endDay = $('.dpd2').val();
    var milestone = getFilterDate(option);
    $.ajax({
        url : prefixRoute + "/update-table",
        type : "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            'startDay' : startDay,
            'endDay' : endDay,
            'timePeriodTitle' : milestone['timePeriodTitle'],
        },
        beforeSend : function () {
            sendingRequestTable();
        },
        success : function (response) {
            $('.table_data_report').html(response.tableDataLayout);
            $('.summary_report').html(response.summaryReportLayout);
            processDataTable(response);
            history.pushState("", "", link);
        },
        error : function (response) {
            alert('Something went wrong!');
        },
        complete : function () {
            completeRequestTable();
        }
    });
});
/*
*
* onclicking status button
* update table with selected status
*/
$('.status-option li').click(function () {
    var option = $(this).data('status');
    var status;
    var statusTitle = '';
    switch(option) {
        case 'showZero' :
            statusTitle = 'Show 0';
            status = 'showZero';
            break;
        case 'hideZero' :
            statusTitle = 'Hide 0';
            status = 'hideZero';
            break;
    }
    $.ajax({
        url : prefixRoute + "/update-table",
        type : "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            'status' : status,
            'statusTitle' : statusTitle,
        },
        beforeSend : function () {
            sendingRequestTable();
        },
        success : function (response) {
            $('.table_data_report').html(response.tableDataLayout);
            $('.summary_report').html(response.summaryReportLayout);
            processDataTable(response);
            history.pushState("", "", link);
        },
        error : function (response) {
            alert('Something went wrong!');
        },
        complete : function () {
            completeRequestTable();
        }
    });
});
/*
*
* select all checkbox
*/
$("#selectAll").click(function () {
    if(this.checked) {
      // Iterate each checkbox
      $(':checkbox').each(function() {
          this.checked = true;
      });
      filterColumnChecked();
    }
    else {
        $(':checkbox').each(function () {
            this.checked = false;
            $("input[name='fieldName']:checkbox")[0].checked = true;
            filterColumnChecked();
        });
    }
})
/*
*
* onclicking table header
* sort table
*/
$('.table_data_report').delegate('th', 'click', function() {
    var th = $("th").eq($(this).index());
    $.ajax({
        url : prefixRoute + "/update-table",
        type : "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            'columnSort' : th.data('value'),
        },
        success : function (response) {
            $('.table_data_report').html(response.tableDataLayout);
        }
    });
})

$('.specific-filter-item').click(function() {
    $.ajax({
        url : prefixRoute + "/update-table",
        type : "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            'specificItem' : $(this).data('value'),
        },
        beforeSend : function () {
            $('html, body').animate({
                scrollTop: $('#active-scroll').offset().top
            }, 1000)
            sendingRequestTable();
        },
        success : function (response) {
            $('.table_data_report').html(response.tableDataLayout);
            $('.summary_report').html(response.summaryReportLayout);
        },
        complete : function () {
            completeRequestTable();
        }
    });
});

$('.normal-report').click(function() {
    $.ajax({
        url : prefixRoute + "/update-table",
        type : "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            'normalReport' : 'normal-report',
        },
        beforeSend : function () {
            sendingRequestTable();
        },
        success : function (response) {
            $('.table_data_report').html(response.tableDataLayout);
        },
        complete : function () {
            completeRequestTable();
        }
    });
});

//dynamically display site title(above graph)
$(document).ready(function(){
    let array = [];
    let objectAccount = new Object();
    let objectCampaign = new Object();
    let objectAdgroup = new Object();
    let objectKeyword = new Object();
    let objectAd = new Object();
    let objectUser = new Object();

    objectUser['title'] = 'Client';
    objectUser['name'] = 'Client';
    objectUser['engine'] = "";
    objectUser['value'] = $('#username').attr('value');
    array.push(objectUser);

    var engine = $('select.id_Account').find(':selected').attr('data-engine');

    var iconEngine = '<img src="images/yahoo.png" width="15px" height="15px" class="iconMedia" >';

    if(engine === 'adw') {
        iconEngine = '<img src="images/adwords.png" width="15px" height="15px" class="iconMedia" >';
    }

    objectAccount['title'] = 'Account';
    objectAccount['name'] = $('select.id_Account').find(':selected').attr('data-breadcumbs');
    objectAccount['value'] = $('select.id_Account').find(':selected').attr('data-tokens');
    objectAccount['engine'] = iconEngine;
    array.push(objectAccount);

    objectCampaign['title'] = 'Campaign';
    objectCampaign['name'] = $('select.id_Campaign').find(':selected').attr('data-breadcumbs');
    objectCampaign['value'] = $('select.id_Campaign').find(':selected').attr('data-tokens');
    objectCampaign['engine'] = iconEngine;
    array.push(objectCampaign);

    objectAdgroup['title'] = 'Adgroup';
    objectAdgroup['name'] = $('select.id_AdGroup').find(':selected').attr('data-breadcumbs');
    objectAdgroup['value'] = $('select.id_Campaign').find(':selected').attr('data-tokens');
    objectAdgroup['engine'] = iconEngine;
    array.push(objectAdgroup);

    objectKeyword['title'] = 'Keyword';
    objectKeyword['name'] = $('select.id_KeyWord').find(':selected').attr('data-breadcumbs');
    objectKeyword['value'] = $('select.id_AdGroup').find(':selected').attr('data-tokens');
    objectKeyword['engine'] = iconEngine;
    array.push(objectKeyword);

    objectAd['title'] = 'Ad';
    objectAd['name'] = $('select.id_Ad').find(':selected').attr('data-breadcumbs');
    objectAd['value'] = $('select.id_AdGroup').find(':selected').attr('data-tokens');
    objectAd['engine'] = iconEngine;
    array.push(objectAd);

    let pageInformation = null;
    let count = array.length-1;
    while(count >= 0){
        if (array[count].name !== 'all' && array[count].name !== undefined){
            pageInformation = array[count];
            break;
        }
        count--;
    }
    if ($('span.title').attr('data-titleBreadCumbs') == 'アカウント名') {
        if (pageInformation.title == 'Client') {
            $('.site-information-guess-annotation').append('クライエント');
        }
        else if(pageInformation.title == 'Account'){
            $('.site-information-guess-annotation').append('アカウント名');
        }
        else if(pageInformation.title == 'Campaign'){
            $('.site-information-guess-annotation').append('キャンペーン');
        }
        else if(pageInformation.title == 'Keyword'){
            $('.site-information-guess-annotation').append('キーワード');
        }
        else if(pageInformation.title == 'Adgroup'){
            $('.site-information-guess-annotation').append('広告グループ');
        }
        else if(pageInformation.title == 'Ad'){
            $('.site-information-guess-annotation').append('広告');
        }
    }else{
        $('.site-information-guess-annotation').append(pageInformation.title);
    }
    $('.site-information-guess-specified-name').append(pageInformation.engine + ' ' +pageInformation.value);
})
