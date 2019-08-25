function login()
{
    var loginName = $("input[name*='userLogin']").val();
    var loginPassword = $("input[name*='userPassword']").val();

    var form_data = {
        "userLogin"     : loginName,
        "userPassword"  : loginPassword
    };

    $.ajax({
        url: 'api/action/login.php',
        type: 'POST',
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(form_data),
        dataType: 'json',
        statusCode: {
            200: function() {
                location.reload();
            },
            400: function(response) {
                alert(response.responseJSON.message);
            }
        }
    });
}

function rememberPassword() {
    var userLogin = {
        "userLogin" : prompt("Wprowadź login", "")
    }

    $.ajax({
        url: 'api/action/rememberPassword.php',
        type: 'POST',
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        data: JSON.stringify(userLogin),
        statusCode: {
            200: function(response) {
                alert(response.message);
            },
            400: function(response) {
                alert(response.responseJSON.message);
            }
        }
    });
}

function registerAccount() {
    var familyName = $("input[name*='newFamilyName']").val();
    var userLogin = $("input[name*='newUserLogin']").val();
    var userEmail = $("input[name*='newUserEmail']").val();
    var userType = $("select[name*='userType']").val();
    var userPassword = $("input[name*='newUserPassword']").val();
    var userPassword2 = $("input[name*='newUserPassword2']").val();

    if(userPassword == userPassword2)
    {
        var form_data = {
            "familyName"    : familyName,
            "userLogin"     : userLogin,
            "userEmail"     : userEmail,
            "userPassword"  : userPassword,
            "userType"      : userType
        };

        $.ajax({
            url: 'api/action/register.php',
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(form_data),
            dataType: 'json',
            statusCode: {
                200: function(response) {
                    alert(response.message);
                },
                400: function(response) {
                    alert(response.responseJSON.message);
                }
            }
        });
    } else {
        alert("NIE ZGADZAJA SIE HASLA");
    }
}

function fillUserPanel() {
    $.ajax({
        url: 'api/action/readUser.php',
        type: 'POST',
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        statusCode: {
            200: function(response) {
                $("input[name*='editUserLogin']").val(response.userLogin);
                $("input[name*='editUserEmail']").val(response.userEmail);
            },
            400: function(response) {
                alert(response.responseJSON.message);
            }
        }
    });
}

function editUser() {
    var userLogin = $("input[name*='editUserLogin']").val();
    var userEmail = $("input[name*='editUserEmail']").val();
    var actualPassword = $("input[name*='actualPassword']").val();

    var form_data = {
        "userLogin"         : userLogin,
        "userEmail"         : userEmail,
        "actualPassword"    : actualPassword
    };

    $.ajax({
        url: 'api/action/userPanel/editUser.php',
        type: 'POST',
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        data: JSON.stringify(form_data),
        statusCode: {
            200: function(response) {
                alert(response.message);
            },
            400: function(response) {
                alert(response.responseJSON.message);
            },
            503: function(response) {
                alert(JSON.stringify(response));
            }
        }
    });
}

function changePassword() {
    var userPassword = $("input[name*='newUserPassword']").val();
    var userPassword2 = $("input[name*='newUserPassword2']").val();
    var actualPassword = $("input[name*='actualPassword2']").val();
    
    if(userPassword == userPassword2)
    {
        var form_data = {
            "newUserPassword"      : userPassword,
            "actualPassword"    : actualPassword
        };

        $.ajax({
            url: 'api/action/userPanel/changePassword.php',
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: 'json',
            data: JSON.stringify(form_data),
            statusCode: {
                200: function(response) {
                    alert(response.message);
                },
                400: function(response) {
                    alert(response.responseJSON.message);
                },
                503: function(response) {
                    alert(JSON.stringify(response));
                }
            }
        });
    } else {
        alert("Hasla sie nie zgadzaja");
    }
}

function logoutUser()
{
    var logout = $.post("api/action/logout.php");
    logout.done(function()
    {
        location.reload();
    }, "json");
}