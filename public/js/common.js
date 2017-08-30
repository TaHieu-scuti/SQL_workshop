/*
=======
* stop drop-down menu form disappearing on clicking
*/
$('.dropdown-menu').click(function (e) {
    e.stopPropagation(); 
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
* onclicking apply button
* display selected columns
* update table with selected columns
*/
$(".apply-button").click(function () {
    $array = [];
    if (!$array['fieldName']) {
        $array['fieldName'] = [];
    }

    if(!$array['paginaton']) {
        $array['pagination'] = $("input[name='resultPerPage']:checked").val();
    }

    $.each($("input[name='fieldName']:checked"), function () {
        $array['fieldName'].push($(this).val());
    });
    
    $.ajax({
        url: "/update-table",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            'pagination' : $array['pagination'],
            'fieldName' : $array['fieldName'],
        },
        success: function(result) {
            $('table').html(result);
            $('#columnsModal').modal('hide');
        }
    });
});
/*
*
* onclicking date button
* update table with selected time period
*/
$('.date-option li').click(function () {
    var option = $(this).data('date');
    var milestone = getFilterDate(option);
    $.ajax({
        url : "/update-table",
        type : "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            'startDay' : milestone['startDay'],
            'endDay' : milestone['endDay']
        },
        success : function (response) {
            $('table').html(response);
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
    switch(option) {
        case 'all' : 
            status = '';
            break;
        case 'disabled' : 
            status = 'disabled';
            break;
        case 'enabled' :
            status = 'enabled';
            break;
    }
    $.ajax({
        url : "/update-table",
        type : "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            'status' : status,
        },
        success : function (response) {
            $('table').html(response);
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
    }
    else {
        $(':checkbox').each(function () {
            this.checked = false;
        });
    }
})
/*
*
* onclicking table header
* sort table
*/
$('table').delegate('th', 'click', function() {
    var th = $("th").eq($(this).index());
    $.ajax({
        url : "/update-table",
        type : "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            'columnSort' : th.text(),
        },
        success : function (response) {
            $('table').html(response);
        }
    });
}) 

var timer;
function searchUp() {
    timer = setTimeout(function()
    {
        var keywords = $('#txtLiveSearch').val();
        if (keywords.length >= 0) {
            $.ajax({
                url: "account_report/live_search",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'keywords' : keywords,
                },
                success: function(result) {
                    $('#listSearch').empty();
                    $('#listSearch').html(result);
                }
            });
        }
    }, 500);
} 

$('#listSearch').delegate('li', 'click', function() {
    $('#txtColumn').text($(this).text());
    if($(".selection-dropdown").hasClass("open")){
        $(".selection-dropdown").removeClass("open");
    }
})
