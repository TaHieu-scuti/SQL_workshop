var prefixRoute = getRoutePrefix();

function updateAdwApi() {
    let id = $('#adw_id').val().trim();
    let url = prefixRoute +'/store-account';
    if (id) {
        url = prefixRoute +'/update-account/' + id;
    }

    $.ajax({
        url: url,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            'account_id' : $('#account_id').val(),
            'media' : 0,
            'userAgent' : $('#adw_form #userAgent').val(),
            'developerToken' : $('#adw_form #developerToken').val(),
            'clientCustomerId' : $('#adw_form #clientCustomerId').val(),
            'onBehalfOfAccountId' : $('#adw_form #onBehalfOfAccountId').val(),
            'onBehalfOfPassword' : $('#adw_form #onBehalfOfPassword').val(),
        },
        beforeSend : function () {
        },
        success: function(response) {
            if (response.create === 'success') {
                $('#btn_cancel').css('display', 'block');
                alert('Create success');
            } else if (response.update === 'success') {
                alert('Update success');
            }
            $('.alert-danger').parent().empty();
        },
        error : function (response) {
            if (checkErrorAjax(response)) {
                if (isJson(response.responseText)) {
                    let obj = JSON.parse(response.responseText);
                    for (let i in obj) {
                        str = '#adw_form #error_' + i;
                        $(str).html('<div class="alert alert-danger">' + obj[i] + '</div>');
                    }
                }
            }
        },
        complete : function () {
        }
    });
}

function updateYdnApi() {
    let id = $('#ydn_id').val().trim();
    let url = prefixRoute +'/store-account';
    if (id) {
        url = prefixRoute +'/update-account/' + id;
    }

    $.ajax({
        url: url,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            'account_id' : $('#account_id').val(),
            'media' : 1,
            'userAgent' : $('#ydn_form #userAgent').val(),
            'license' : $('#ydn_form #license').val(),
            'accountId' : $('#ydn_form #accountId').val(),
            'apiAccountId' : $('#ydn_form #apiAccountId').val(),
            'apiAccountPassword' : $('#ydn_form #apiAccountPassword').val(),
        },
        beforeSend : function () {
        },
        success: function(response) {
            if (response.create === 'success') {
                $('#btn_cancel').css('display', 'block');
                alert('Create success');
            } else if (response.update === 'success') {
                alert('Update success');
            }
            $('.alert-danger').parent().empty();
        },
        error : function (response) {
            if (checkErrorAjax(response)) {
                if (isJson(response.responseText)) {
                    let obj = JSON.parse(response.responseText);
                    for (let i in obj) {
                        str = '#ydn_form #error_' + i;
                        $(str).html('<div class="alert alert-danger">' + obj[i] + '</div>');
                    }
                }
            }
        },
        complete : function () {
        }
    });
}

function updateYssApi() {
    let id = $('#yss_id').val().trim();
    let url = prefixRoute +'/store-account';
    if (id) {
        url = prefixRoute +'/update-account/' + id;
    }

    $.ajax({
        url: url,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            'account_id' : $('#account_id').val(),
            'media' : 2,
            'userAgent' : $('#yss_form #userAgent').val(),
            'license' : $('#yss_form #license').val(),
            'accountId' : $('#yss_form #accountId').val(),
            'apiAccountId' : $('#yss_form #apiAccountId').val(),
            'apiAccountPassword' : $('#yss_form #apiAccountPassword').val(),
        },
        beforeSend : function () {
        },
        success: function(response) {
            if (response.create === 'success') {
                $('#btn_cancel').css('display', 'block');
                alert('Create success');
            } else if (response.update === 'success') {
                alert('Update success');
            }
            $('.alert-danger').parent().empty();
        },
        error : function (response) {
            if (checkErrorAjax(response)) {
                if (isJson(response.responseText)) {
                    let obj = JSON.parse(response.responseText);
                    for (let i in obj) {
                        str = '#yss_form #error_' + i;
                        $(str).html('<div class="alert alert-danger">' + obj[i] + '</div>');
                    }
                }
            }
        },
        complete : function () {
        }
    });
}

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
    }
    return true;
}