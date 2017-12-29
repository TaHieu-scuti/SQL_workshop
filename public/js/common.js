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

function sendingRequestTable() {
    $('.report-table').css('display', 'none');
    $('.loading-gif-on-table').removeClass('hidden-table');
    $('.loading-gif-on-table').show();
}

function showLoadingImageOnTopGraph() {
    $('.loading-gif-on-top-graph').removeClass('hidden-graph');
    setTimeout(function() {
        $('.loading-gif-on-top-graph').show();
    }, 10);
}

function completeRequestTable()
{
    $('.loading-gif-on-table').addClass('hidden-table');
    $('.loading-gif-on-top-graph').addClass('hidden-graph');
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
            $('#columnsModal').modal('hide');
            sendingRequestTable();
        },
        success: function(response) {
            $('.table_data_report').html(response.tableDataLayout);
            $('.summary_report').html(response.summaryReportLayout);
            processDataTable(response);
            history.pushState("", "", link);
        },
        error : function (response) {
            checkErrorAjax(response);
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
            checkErrorAjax(response);
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
            checkErrorAjax(response);
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
            checkErrorAjax(response);
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
        },
        error : function (response) {
            checkErrorAjax(response);
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
            sendingRequestTable();
            showLoadingImageOnTopGraph();
        },
        success : function (response) {
            $('.table_data_report').html(response.tableDataLayout);
            $('.summary_report').html(response.summaryReportLayout);
        },
        error : function (response) {
            checkErrorAjax(response);
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
        error : function (response) {
            checkErrorAjax(response);
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
    let objectClient = new Object();
    let objectAgency = new Object();
    let objectCampaign = new Object();
    let objectAdgroup = new Object();
    let objectKeyword = new Object();
    let objectAd = new Object();
    let objectUser = new Object();

    if (getLevelCurrentUser() === 'admin') {
        objectUser['title'] = 'Admin';
        objectUser['name'] = 'Admin';
    } else if (getLevelCurrentUser() === 'agency') {
        objectUser['title'] = 'Agency';
        objectUser['name'] = 'Agency';
    } else {
        objectUser['title'] = 'Direct Client';
        objectUser['name'] = 'Direct Client';
    }
    objectUser['engine'] = "";
    objectUser['value'] = $('#username').attr('value');
    array.push(objectUser);

    var engine = $('select.id_Account').find(':selected').attr('data-engine');

    var iconEngine = '<img src="images/yahoo.png" width="15px" height="15px" class="iconMedia" >';

    if(engine === 'adw') {
        iconEngine = '<img src="images/adwords.png" width="15px" height="15px" class="iconMedia" >';
    }

    objectAgency['title'] = 'Agency';
    objectAgency['name'] = $('select.id_Agency').find(':selected').attr('data-breadcumbs');
    objectAgency['value'] = $('select.id_Agency').find(':selected').attr('data-tokens');
    objectAgency['engine'] = '';
    array.push(objectAgency);

    if ($('.id_Client').length > 0) {
        objectClient['title'] = 'Client';
        objectClient['name'] = $('select.id_Client').find(':selected').attr('data-breadcumbs');
        objectClient['value'] = $('select.id_Client').find(':selected').attr('data-tokens');
    } else if ($('.id_Direct').length > 0) {
        objectClient['title'] = 'Direct Client';
        objectClient['name'] = $('select.id_Direct').find(':selected').attr('data-breadcumbs');
        objectClient['value'] = $('select.id_Direct').find(':selected').attr('data-tokens');
    }
    objectClient['engine'] = '';
    array.push(objectClient);

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
    objectAdgroup['value'] = $('select.id_AdGroup').find(':selected').attr('data-tokens');
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
            $('.site-information-guess-annotation').append('クライアント');
        }
        else if(pageInformation.title == 'Account'){
            $('.site-information-guess-annotation').append('アカウント名');
        }
        else if(pageInformation.title == 'Campaign'){
            $('.site-information-guess-annotation').append('キャンペーン');
            $('.campaign-navigation').hide();
        }
        else if(pageInformation.title == 'Adgroup'){
            $('.site-information-guess-annotation').append('広告グループ');
            $('.campaign-navigation').hide();
            $('.adgroup-navigation').hide();
        }
    } else {
        if(pageInformation.title == 'Campaign'){
            $('.site-information-guess-annotation').append(pageInformation.title);
            $('.campaign-navigation').hide();
        }
        else if(pageInformation.title == 'Adgroup'){
            $('.site-information-guess-annotation').append(pageInformation.title);
            $('.campaign-navigation').hide();
            $('.adgroup-navigation').hide();
        }
        else {
            $('.site-information-guess-annotation').append(pageInformation.title);
        }
    }
    $('.site-information-guess-specified-name').append(pageInformation.engine + ' ' +pageInformation.value);
})

function isJson(obj) {
    try {
        JSON.parse(obj);
    } catch (e) {
        return false;
    }
    return true;
}

function checkErrorAjax (response) {
    // XMLHttpRequest.readyState === 4: The operation is complete.
    if (response.readyState !== 4) {
        return false;
    }
    if (response.status === 403 && isJson(response.responseText)) {
        let obj = JSON.parse(response.responseText);
        if (obj.error === 'session_expired') {
            alert('Session expired');
            window.location.href = obj.redirect_url;
        }
    } else {
        alert('Something went wrong!');
        console.error(response.statusText);
    }
}
