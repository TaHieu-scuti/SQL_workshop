function getFilterDate(option)
{
    var milestoneArray = [];
    var endDay;
    var startDay;
    var today = moment();
    switch(option) {
        case 'today' : 
            endDay = today.format("YYYY-MM-DD");
            startDay = endDay;
            break;
        case 'yesterday' : 
            endDay = today.subtract(1, 'd').format("YYYY-MM-DD");
            startDay = endDay;
            break;
        case 'last7days' :
            endDay = today.format("YYYY-MM-DD");
            startDay = today.subtract(7, 'd').format("YYYY-MM-DD");
            break;
        case 'last30days' :
            endDay = today.format("YYYY-MM-DD");
            startDay = today.subtract(30, 'd').format("YYYY-MM-DD");
            break;
        case 'last90days' :
            endDay = today.format("YYYY-MM-DD");
            startDay = today.subtract(90, 'd').format("YYYY-MM-DD");
            break;
        case 'thisWeek' :
            endDay = today.format("YYYY-MM-DD");
            startDay = today.startOf('isoweek').format("YYYY-MM-DD");
            break;
        case 'thisMonth' :
            endDay = today.format("YYYY-MM-DD");
            startDay = today.startOf('month').format("YYYY-MM-DD");
            break;
        case 'thisQuarter' :
            endDay = today.format("YYYY-MM-DD");
            startDay = today.startOf('quarter').format("YYYY-MM-DD");
            break;
        case 'thisYear' :
            endDay = today.format("YYYY-MM-DD");
            startDay = today.startOf('year').format("YYYY-MM-DD");
            break;
        case 'lastBusinessWeek' :
            endDay = moment(moment().subtract(1, 'weeks')).day(3).format("YYYY-MM-DD");
            startDay = moment().subtract(1, 'weeks').startOf('isoWeek').format("YYYY-MM-DD");
            break;
        case 'last7DaysToday' :
            endDay = today.add(1, 'days').format("YYYY-MM-DD");
            startDay = today.subtract(7, 'd').format("YYYY-MM-DD");
            break;
        case 'lastFullWeek' :
            endDay = moment().subtract(1, 'weeks').endOf('isoWeek').format("YYYY-MM-DD");
            startDay = moment().subtract(1, 'weeks').startOf('isoWeek').format("YYYY-MM-DD");
            break;
        case 'lastMonth' :
            endDay = moment().subtract(1, 'months').endOf('month').format("YYYY-MM-DD");
            startDay = moment().subtract(1, 'months').startOf('month').format("YYYY-MM-DD");
            break;
        case 'lastQuarter' :
            endDay = moment().subtract(1, 'quarters').endOf('quarter').format("YYYY-MM-DD");
            startDay = moment().subtract(1, 'quarters').startOf('quarter').format("YYYY-MM-DD");
            break;
        case 'lastYear' :
            endDay = moment().subtract(1, 'years').endOf('year').format("YYYY-MM-DD");
            startDay = moment().subtract(1, 'years').startOf('year').format("YYYY-MM-DD");
            break;
    }
    if (!milestoneArray['endDay']) {
        milestoneArray['endDay'] = endDay;
    }
    if (!milestoneArray['startDay']) {
        milestoneArray['startDay'] = startDay;
    }
    return milestoneArray;
}