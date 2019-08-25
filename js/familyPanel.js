function fillFamilyPanel() {
    $.ajax({
        url: 'api/action/getFamilyMembers.php',
        type: 'post',
        dataType: 'json',
        statusCode: {
            200: function(response) {
                //WSTAW NAZWE RODZINY
                $("#familyName").html(response.familyName);
                //WSTAW DO ZMIENNEJ DLUGOSC TABLICY Z SERWERA
                var countRow = response.familyMembers.length;
                var tableBody = $("#familyRows");
                for(var i=0; i<countRow; i++) {
                    //DODAJ WIERSZ
                    var tableRow = $("<tr>");
                    tableRow.attr('id', response.familyMembers[i].idUser);
                    tableBody.append(tableRow);
                    //NAZWA USERA
                    var userLoginCell = $("<td>"+response.familyMembers[i].userLogin+"</td>");
                    tableRow.append(userLoginCell);
                    // TYP USERA
                    var userTypeCell = $("<td>");
                    tableRow.append(userTypeCell);
                    var userTypeSelect = $("<select name='userType' class='form-control'><option value='1'>Rodzic</option><option value='2'>Dziecko</option></select>");
                    userTypeCell.append(userTypeSelect);
                    if(response.familyMembers[i].userType == 1) userTypeSelect.val("1");
                    else userTypeSelect.val("2");
                    // UPRAWNIENIA GRUP URZADZEN
                    if(response.familyMembers[i].userType == 2)
                    {
                        if(response.familyMembers[i].deviceGroupPermission == 1) var deviceGroupPermissionChekbox = $("<input class='toggle btn btn-primary' type='checkbox' checked data-toggle='toggle' onclick='changeDeviceGroupPermission("+response.familyMembers[i].idUser+")'>");
                        else var deviceGroupPermissionChekbox = $("<input class='toggle btn btn-primary' type='checkbox' data-toggle='toggle' onclick='changeDeviceGroupPermission("+response.familyMembers[i].idUser+")'>");
                    }
                    var deviceGroupPermissionCell = $("<td>");
                    tableRow.append(deviceGroupPermissionCell);
                    deviceGroupPermissionCell.append(deviceGroupPermissionChekbox);
                    // UPRAWNIENIA URZADZEN
                    if(response.familyMembers[i].userType == 2)
                    {
                        if(response.familyMembers[i].devicePermission == 1) var devicePermissionChekbox = $("<input class='toggle btn btn-primary' type='checkbox' checked data-toggle='toggle' onclick='changeDevicePermission("+response.familyMembers[i].idUser+")'>");
                        else var devicePermissionChekbox = $("<input class='toggle btn btn-primary' type='checkbox' data-toggle='toggle' onclick='changeDevicePermission("+response.familyMembers[i].idUser+")'>");
                    }
                    var devicePermissionCell = $("<td>");
                    tableRow.append(devicePermissionCell);
                    devicePermissionCell.append(devicePermissionChekbox);
                    // UPRAWNIENIA SCEN
                    if(response.familyMembers[i].userType == 2)
                    {
                        if(response.familyMembers[i].scenePermission == 1) var scenePermissionChekbox = $("<input class='toggle btn btn-primary' type='checkbox' checked data-toggle='toggle' onclick='changeScenePermission("+response.familyMembers[i].idUser+")'>");
                        else var scenePermissionChekbox = $("<input class='toggle btn btn-primary' type='checkbox' data-toggle='toggle' onclick='changeScenePermission("+response.familyMembers[i].idUser+")'>");
                    }
                    var scenePermissionCell = $("<td>");
                    tableRow.append(scenePermissionCell);
                    scenePermissionCell.append(scenePermissionChekbox);
                }
            },
            400: function(response) {
                console.log(response);
            }
        }
    });
}

function changeFamilyName() {
    var newFamilyName = {
        "newFamilyName" : prompt("Wprowadź nową nazwę rodziny", "")
    }

    if(newFamilyName.newFamilyName != null && newFamilyName.newFamilyName != "") {
        $.ajax({
            url: 'api/action/familyPanel/editFamilyName.php',
            type: 'POST',
            contentType: "application/json; charset=utf-8",
            dataType: 'json',
            data: JSON.stringify(newFamilyName),
            statusCode: {
                200: function(response) {
                    $("#familyName").html(newFamilyName.newFamilyName);
                    alert(response.message);
                },
                400: function(response) {
                    alert(response.responseJSON.message);
                }
            }
        });
    }
    else alert("Nie wprowadziłeś nazwy.");
}

function changeDeviceGroupPermission(idUser) {
    var data = {
        "idUser" : idUser
    }
    $.ajax({
        url: 'api/action/familyPanel/changeDeviceGroupPermission.php',
        type: 'POST',
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        data: JSON.stringify(data),
        statusCode: {
            200: function(response) {
                console.log(response);
            },
            400: function(response) {
                alert(response.responseJSON.message);
            }
        }
    });    
}

function changeDevicePermission(idUser) {
    var data = {
        "idUser" : idUser
    }
    $.ajax({
        url: 'api/action/familyPanel/changeDevicePermission.php',
        type: 'POST',
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        data: JSON.stringify(data),
        statusCode: {
            200: function(response) {
                console.log(response);
            },
            400: function(response) {
                alert(response.responseJSON.message);
            }
        }
    });    
}

function changeScenePermission(idUser) {
    var data = {
        "idUser" : idUser
    }
    $.ajax({
        url: 'api/action/familyPanel/changeScenePermission.php',
        type: 'POST',
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        data: JSON.stringify(data),
        statusCode: {
            200: function(response) {
                console.log(response);
            },
            400: function(response) {
                alert(response.responseJSON.message);
            }
        }
    });    
}

$('body').on('change', "select[name*='userType']", function (e) {
    // var optionSelected = $("option:selected", this);
    // console.log(optionSelected);
    var idUser = $(this).closest("tr").attr("id");
    var userType = this.value;
    var data = {
        "idUser"    : idUser,
        "userType"  : userType
    }

    $.ajax({
        url: 'api/action/familyPanel/changeUserType.php',
        type: 'POST',
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        data: JSON.stringify(data),
        statusCode: {
            200: function(response) {
                console.log(response);
                //rozwiazanie chwilowe, potem trzeba asynchronicznie wstawic checkboxy
                location.reload();
            },
            400: function(response) {
                alert(response.responseJSON.message);
            }
        }
    }); 
});