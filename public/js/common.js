/*
        * stop drop-down menu form disappearing on clicking
        */
        $('.dropdown-menu').click(function(e) {
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