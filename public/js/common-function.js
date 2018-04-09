function getFilterDate(option)
{
    var milestoneArray = [];
    var endDay;
    var startDay;
    var timePeriodTitle;
    var today = moment();
    switch(option) {
        case 'today' :
            timePeriodTitle = 'Today';
            endDay = today.format("YYYY-MM-DD");
            startDay = endDay;
            break;
        case 'yesterday' :
            timePeriodTitle = 'Yesterday';
            endDay = today.subtract(1, 'd').format("YYYY-MM-DD");
            startDay = endDay;
            break;
        case 'last7days' :
            timePeriodTitle = 'Last 7 days( exclude today)';
            endDay = today.subtract(1, 'd').format("YYYY-MM-DD");
            startDay = today.subtract(7, 'd').format("YYYY-MM-DD");
            break;
        case 'last30days' :
            timePeriodTitle = 'Last 30 days';
            endDay = today.format("YYYY-MM-DD");
            startDay = today.subtract(30, 'd').format("YYYY-MM-DD");
            break;
        case 'last90days' :
            timePeriodTitle = 'Last 90 days';
            endDay = today.format("YYYY-MM-DD");
            startDay = today.subtract(90, 'd').format("YYYY-MM-DD");
            break;
        case 'thisWeek' :
            timePeriodTitle = 'This week';
            endDay = today.format("YYYY-MM-DD");
            startDay = today.startOf('isoweek').format("YYYY-MM-DD");
            break;
        case 'thisMonth' :
            timePeriodTitle = 'This month';
            endDay = today.format("YYYY-MM-DD");
            startDay = today.startOf('month').format("YYYY-MM-DD");
            break;
        case 'thisQuarter' :
            timePeriodTitle = 'This quarter';
            endDay = today.format("YYYY-MM-DD");
            startDay = today.startOf('quarter').format("YYYY-MM-DD");
            break;
        case 'thisYear' :
            timePeriodTitle = 'This year';
            endDay = today.format("YYYY-MM-DD");
            startDay = today.startOf('year').format("YYYY-MM-DD");
            break;
        case 'lastBusinessWeek' :
            timePeriodTitle = 'Last business week (Mon – Fri)';
            endDay = moment().day(-2).format("YYYY-MM-DD");
            startDay = moment().subtract(1, 'weeks').startOf('isoWeek').format("YYYY-MM-DD");
            break;
        case 'last7DaysToday' :
            timePeriodTitle = 'Last 7 days( include today)';
            endDay = today.format("YYYY-MM-DD");
            startDay = today.subtract(6, 'd').format("YYYY-MM-DD");
            break;
        case 'lastFullWeek' :
            timePeriodTitle = 'Last full week';
            endDay = moment().subtract(1, 'weeks').endOf('isoWeek').format("YYYY-MM-DD");
            startDay = moment().subtract(1, 'weeks').startOf('isoWeek').format("YYYY-MM-DD");
            break;
        case 'lastMonth' :
            timePeriodTitle = 'Last month';
            endDay = moment().subtract(1, 'months').endOf('month').format("YYYY-MM-DD");
            startDay = moment().subtract(1, 'months').startOf('month').format("YYYY-MM-DD");
            break;
        case 'lastQuarter' :
            timePeriodTitle = 'Last quarter';
            endDay = moment().subtract(1, 'quarters').endOf('quarter').format("YYYY-MM-DD");
            startDay = moment().subtract(1, 'quarters').startOf('quarter').format("YYYY-MM-DD");
            break;
        case 'lastYear' :
            timePeriodTitle = 'Last year';
            endDay = moment().subtract(1, 'years').endOf('year').format("YYYY-MM-DD");
            startDay = moment().subtract(1, 'years').startOf('year').format("YYYY-MM-DD");
            break;
        case 'custom' :
            timePeriodTitle = 'Custom';
            break;
    }
    if (!milestoneArray['endDay']) {
        milestoneArray['endDay'] = endDay;
    }
    if (!milestoneArray['startDay']) {
        milestoneArray['startDay'] = startDay;
    }
    if (!milestoneArray['timePeriodTitle']) {
        milestoneArray['timePeriodTitle'] = timePeriodTitle;
    }
    return milestoneArray;
}

function sendingRequest()
{
    $('.loading-gif-on-graph').removeClass('hidden-graph');
    $('.loading-gif-on-graph').show();
    global_graph_field_selected = '';
}

function completeRequest()
{
    $('.loading-gif-on-graph').addClass('hidden-graph');
}
