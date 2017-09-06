$(document).ready(function() {
    $('.loading-gif').hide()
    .ajaxStart(function() {
        $(this).show()
    })
    .ajaxStop(function() {
        $(this).hide();
    });
});