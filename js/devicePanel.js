function createDeviceGroup() {
    var deviceGroupName = prompt("Wprowadz nazwe nowej grupy", "");

    var data = {
        "deviceGroupName" : deviceGroupName,
    };

    if(deviceGroupName != null ) {
        $.ajax({
            type: "POST",
            async: false,
            url: 'api/action/devicePanel/createDeviceGroup.php',
            data: JSON.stringify(data),
            success: function(response)
            {
                console.log(response);
            },
            error: function(response)
            {
                alert(response.responseJSON.message);
            }
        });
    }
}

function getDeviceGroup(idDeviceGroup)
{
    var json = null;

    var data = {
        "idDeviceGroup" : idDeviceGroup
    };

    $.ajax({
        type: "POST",
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        async: false,
        url: 'api/action/devicePanel/getDeviceGroup.php',
        data: JSON.stringify(data),
        success: function(response)
        {
            json = response;
        },
        error: function(response)
        {
            console.log(response);
        }
    });
    return json;
}

function getDeviceGroups()
{
    var json = null;
    $.ajax({
        type: "POST",
        async: false,
        url: 'api/action/devicePanel/getDeviceGroups.php',
        success: function(response)
        {
            json = response.deviceGroups;
        }
    });
    return json;
}

function editDeviceGroup(idDeviceGroup) {
    var json = null;
    var deviceGroupName = $("input[name*='newDeviceGroupName']").val();
    var temperatureDevice = $("select[name*='temperatureDevice']").val();

    var data = {
        "idDeviceGroup" : idDeviceGroup,
        "deviceGroupName" : deviceGroupName,
        "temperatureDevice" : temperatureDevice
    };
    $.ajax({
        type: "POST",
        async: false,
        url: 'api/action/devicePanel/editDeviceGroup.php',
        data: JSON.stringify(data),
        success: function(response)
        {
            console.log(response);
            $("#deviceGroupSetting").remove();
            $(".modal-backdrop").remove();
            $(".device-group-title[href='#"+idDeviceGroup+"']").html(deviceGroupName);
        },
        error: function(response)
        {
            console.log(response);
        }
    });
    //return json;
}

function deleteDeviceGroup(idDeviceGroup)
{
    var data = {
        "idDeviceGroup" : idDeviceGroup
    };
    if (confirm("Czy napewno chcesz usunąć grupę?")) {
        $.ajax({
            type: "POST",
            async: false,
            url: 'api/action/devicePanel/deleteDeviceGroup.php',
            data: JSON.stringify(data),
            success: function(response)
            {
                console.log(response);
            },
            error: function(response)
            {
                console.log(response);
            }
        });
    }
}

//OBSLUGA MODALA USTAWIEN GRUPY URZADZEN
function deviceGroupSetting(idDeviceGroup) {
    $.get("templates/modals/deviceGroupSetting.html", function(data){
        $(".container").append(data);
        $('#deviceGroupSetting').modal('show');

        $('#deviceGroupSetting').on('hidden.bs.modal', function () {
            $(this).remove();
        })

        deviceGroup = getDeviceGroup(idDeviceGroup);

        $(".modal-title").html(deviceGroup.deviceGroupName);
        $("input[name*='newDeviceGroupName']").val(deviceGroup.deviceGroupName);

        var temperatureDevices = getTemperatureDevices(idDeviceGroup);
        if(temperatureDevices != null) {
            var countDevices = temperatureDevices.length;
            for(var i=0; i<countDevices; i++) {
                var selectValue = $("<option>");
                selectValue.attr("value", temperatureDevices[i].idDevice);
                selectValue.html(temperatureDevices[i].deviceName);
                $("#temperatureDevice").append(selectValue);
            }
            $("#temperatureDevice").val(deviceGroup.temperatureDevice);
        }
        $("button[name*='editDeviceGroupButton']").attr("onclick", "editDeviceGroup("+idDeviceGroup+")");
    });
}

function getDevice(idDevice)
{
    var json = null;

    var data = {
        "idDevice" : idDevice
    };

    $.ajax({
        type: "POST",
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        async: false,
        url: 'api/action/devicePanel/getDevice.php',
        data: JSON.stringify(data),
        success: function(response)
        {
            json = response;
        },
        error: function(response)
        {
            console.log(response);
        }
    });
    return json;
}

function getDevices(idDeviceGroup)
{
    var json = null;
    var data = {
        "idDeviceGroup" : idDeviceGroup
    };
    $.ajax({
        type: "POST",
        async: false,
        url: 'api/action/devicePanel/getDevices.php',
        data: JSON.stringify(data),
        success: function(response)
        {
            json = response.devices;
        }
    });
    return json;
}

function getTemperatureDevices(idDeviceGroup) {
    var json = null;
    var data = {
        "idDeviceGroup" : idDeviceGroup
    };
    $.ajax({
        type: "POST",
        async: false,
        url: 'api/action/devicePanel/getTemperatureDevices.php',
        data: JSON.stringify(data),
        success: function(response)
        {
            json = response.devices;
        }
    });
    return json;
}

function getLastDeviceMeasurements(idDevice)
{
    var json = null;
    var data = {
        "idDevice" : idDevice
    };
    $.ajax({
        type: "POST",
        async: false,
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        url: 'api/action/devicePanel/getDeviceMeasurements.php',
        data: JSON.stringify(data),
        success: function(response)
        {
            json = response.deviceMeasurements;
        },
        error: function(response)
        {
            console.log(response);
        }
    });
    return json;
}

function setAllRelayInGroup(idDeviceGroup)
{
    var measurementValue;
    if (confirm("OK: Włącz --- Anuluj: Wyłącz")) {
        measurementValue = 1;
    } else {
        measurementValue = 0;
    }
    var json = null;
    var data = {
        "idDeviceGroup" : idDeviceGroup,
        "measurementValue": measurementValue
    };
    $.ajax({
        type: "POST",
        async: false,
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        url: 'api/action/devicePanel/setAllRelayInGroup.php',
        data: JSON.stringify(data),
        success: function(response)
        {
            console.log(response);
        },
        error: function(response)
        {
            console.log(response);
        }
    });
    return json;
}

function setAllBlindInGroup(idDeviceGroup)
{
    var measurementValue;
    if (confirm("OK: Włącz --- Anuluj: Wyłącz")) {
        measurementValue = 1;
    } else {
        measurementValue = 0;
    }
    var json = null;
    var data = {
        "idDeviceGroup" : idDeviceGroup,
        "measurementValue": measurementValue
    };
    $.ajax({
        type: "POST",
        async: false,
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        url: 'api/action/devicePanel/setAllBlindInGroup.php',
        data: JSON.stringify(data),
        success: function(response)
        {
            console.log(response);
        },
        error: function(response)
        {
            console.log(response);
        }
    });
    return json;
}

function setAllLightInGroup(idDeviceGroup)
{
    var measurementValue;
    if (confirm("OK: Włącz --- Anuluj: Wyłącz")) {
        measurementValue = 1;
    } else {
        measurementValue = 0;
    }
    var json = null;
    var data = {
        "idDeviceGroup" : idDeviceGroup,
        "measurementValue": measurementValue
    };
    $.ajax({
        type: "POST",
        async: false,
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        url: 'api/action/devicePanel/setAllLightInGroup.php',
        data: JSON.stringify(data),
        success: function(response)
        {
            console.log(response);
        },
        error: function(response)
        {
            console.log(response);
        }
    });
    return json;
}

function changeState(obj, id)
{
    var deviceMeasurementValue;
    if(obj.checked) deviceMeasurementValue = 1;
    else deviceMeasurementValue = 0;

    console.log(deviceMeasurementValue);

    var json = null;
    var data = {
        "idDeviceMeasurement" : id,
        "deviceMeasurementValue" : deviceMeasurementValue
    };

    $.ajax({
        type: "POST",
        async: false,
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        url: 'api/action/devicePanel/changeRelayState.php',
        data: JSON.stringify(data),
        success: function(response)
        {
            console.log(response);
        },
        error: function(response)
        {
            console.log(response);
        }
    });
    return json;
}

function editDevice(idDevice) {

    var deviceName = $("input[name*='newDeviceName']").val();
    var idDeviceGroup = $("select[name*='deviceGroup']").val();

    var data = {
        "idDevice" : idDevice,
        "deviceName" : deviceName,
        "idDeviceGroup" : idDeviceGroup
    };
    $.ajax({
        type: "POST",
        async: false,
        url: 'api/action/devicePanel/editDevice.php',
        data: JSON.stringify(data),
        success: function(response)
        {
            console.log(response);
            location.reload();
            // $("#deviceSetting").remove();
            // $(".modal-backdrop").remove();
            // $(".device-title[href='#"+idDevice+"']").html(deviceName);
        },
        error: function(response)
        {
            alert(response.responseJSON.message);
        }
    });
    //return json;
}

//OBSLUGA MODALA USTAWIEN GRUPY URZADZEN
function deviceSetting(idDevice) {
    $.get("templates/modals/deviceSetting.html", function(data){
        $(".container").append(data);
        $('#deviceSetting').modal('show');

        $('#deviceSetting').on('hidden.bs.modal', function () {
            $(this).remove();
        })

        device = getDevice(idDevice);

        $(".modal-title").html(device.deviceName);
        $("input[name*='newDeviceName']").val(device.deviceName);

        var deviceGroups = getDeviceGroups();

        if(deviceGroups != null) {
            var countDeviceGroups = deviceGroups.length;
            for(var i=0; i<countDeviceGroups; i++) {
                var selectValue = $("<option>");
                selectValue.attr("value", deviceGroups[i].idDeviceGroup);
                selectValue.html(deviceGroups[i].deviceGroupName);
                $("#deviceGroup").append(selectValue);
            }
            $("#deviceGroup").val(device.idDeviceGroup);
        }
        $("button[name*='editDeviceButton']").attr("onclick", "editDevice('"+idDevice+"')");
    });
}


function fillDevicePanel() {
    var deviceSection = $("#device-panel");
    var deviceGroups = getDeviceGroups();
    var countGroups = deviceGroups.length;
    for(var i=0; i<countGroups; i++) {
        //UTWORZ OBIEKTY NAGLOWKA DO WSTAWIENIA
        var deviceGroupContainer = $("<div class='device-group-container'>");
        var deviceGroupHeader = $("<div class='device-group-header'>");
        var deviceGroupSettingButton = $("<button class='button-icon'>");
        var deviceGroupTitle = $("<a data-toggle='collapse' class='device-group-title'>");
        var switchAllRelayButton = $("<button class='button-icon'>");
        var switchAllBlindButton = $("<button class='button-icon'>");
        var switchAllLightButton = $("<button class='button-icon'>");
        var deleteDeviceGroupButton = $("<button class='button-icon'>");

        //WSTAW OBRAZKI DO PRZYCISKOW, TEKST DO TYTULU ORAZ DODAJ POTRZBNE ATRYBUTY
        deviceGroupSettingButton.append("<img src='img/icons/settings.png'>");
        deviceGroupSettingButton.attr("onclick", "deviceGroupSetting("+deviceGroups[i].idDeviceGroup+")");
        deviceGroupTitle.html(deviceGroups[i].deviceGroupName);
        deviceGroupTitle.attr("href", "#"+deviceGroups[i].idDeviceGroup);
        deviceGroupTitle.attr("data-parent", "#device-panel");
        switchAllLightButton.append("<img src='img/icons/light-bulb.png'>");
        switchAllBlindButton.append("<img src='img/icons/blinds.png'>");
        switchAllRelayButton.append("<img src='img/icons/turn-off.png'>");
        deleteDeviceGroupButton.append("<img src='img/icons/delete.png'>");

        switchAllRelayButton.attr("onclick", "setAllRelayInGroup("+deviceGroups[i].idDeviceGroup+")");
        switchAllBlindButton.attr("onclick", "setAllBlindInGroup("+deviceGroups[i].idDeviceGroup+")");
        switchAllLightButton.attr("onclick", "setAllLightInGroup("+deviceGroups[i].idDeviceGroup+")");
        deleteDeviceGroupButton.attr("onclick", "deleteDeviceGroup("+deviceGroups[i].idDeviceGroup+")");
        
        //WSTAW NAGLOWKI GRUPY URZADZEN DO PANELU URZADZEN
        deviceSection.append(deviceGroupContainer);
        deviceGroupContainer.append(deviceGroupHeader);
        deviceGroupHeader.append(deviceGroupSettingButton);
        deviceGroupHeader.append(deviceGroupTitle);
        deviceGroupHeader.append(switchAllRelayButton);
        deviceGroupHeader.append(switchAllBlindButton);
        deviceGroupHeader.append(switchAllLightButton);
        deviceGroupHeader.append(deleteDeviceGroupButton);

        // KONIEC NAGLOWKA GRUPY URZADZEN, DO PRZYCISKOW WSTAWIC AKCJE ONCLICK

        // CIALO GRUPY URZADZEN
        deviceGroupBody = $("<div class='device-group-body collapse' id='"+deviceGroups[i].idDeviceGroup+"'>");
        deviceGroupContainer.append(deviceGroupBody);

        var devices = getDevices(deviceGroups[i].idDeviceGroup);
        if(devices) {
            var countDevices = devices.length;
            for(var j=0; j<countDevices; j++) {
                var deviceContainer = $("<div class='device-container'>");
                deviceGroupBody.append(deviceContainer);

                var deviceHeader = $("<div class='device-header'>");
                deviceContainer.append(deviceHeader);

                var deviceSettingButton = $("<button class='button-icon'>");
                deviceSettingButton.attr("onclick", "deviceSetting('"+devices[j].idDevice+"')");
                deviceSettingButton.append("<img src='img/icons/settings.png'>");
                deviceHeader.append(deviceSettingButton);

                var deviceTitle = $("<div class='device-title'>");
                deviceHeader.append(deviceTitle);
                deviceTitle.html(devices[j].deviceName.substr(0, 32));
                //+"\u2026"

                var deleteDeviceButton = $("<button class='button-icon'>");
                deleteDeviceButton.attr("onclick", "deleteDevice('"+devices[j].idDevice+"')");
                deleteDeviceButton.append("<img src='img/icons/delete.png'>");
                deviceHeader.append(deleteDeviceButton);

                var deviceBody = $("<div class='device-body'>");
                deviceContainer.append(deviceBody);

                var deviceImage = $("<img class='device-image'>");
                var measurementList = $("<ul class='device-measurement'>");
                deviceBody.append(deviceImage);
                deviceBody.append(measurementList);

                if(devices[j].idDeviceType == 1) deviceImage.attr("src", "img/devices/relay-socket-x1.jpg");              
                else if(devices[j].idDeviceType == 2) deviceImage.attr("src", "img/devices/relay-socket-x2.png"); 
                else if(devices[j].idDeviceType == 3) deviceImage.attr("src", "img/devices/relay-socket-x4.png"); 
                else if(devices[j].idDeviceType == 4) deviceImage.attr("src", "img/devices/weather-station.png"); 
                else if(devices[j].idDeviceType == 5) deviceImage.attr("src", "img/devices/pir-sensor.png"); 
                else if(devices[j].idDeviceType == 6) deviceImage.attr("src", "img/devices/temperature-sensor.png"); 
                else if(devices[j].idDeviceType == 7) deviceImage.attr("src", "img/devices/sprinkler-garden.png"); 
                else if(devices[j].idDeviceType == 8) deviceImage.attr("src", "img/devices/relay-light-x1.png");
                else deviceBody.html("Nieznane urzadzenie");

                var deviceMeasurements = getLastDeviceMeasurements(devices[j].idDevice);
                if(deviceMeasurements) {
                    var countMeasurement = deviceMeasurements.length;
                    for (var k = 0; k < countMeasurement; k++) {
                        var singleMeasurement = $("<li>");

                        measurementList.append(singleMeasurement);

                        if (deviceMeasurements[k].idMeasurementType == 1) {
                            singleMeasurement.html("T: "+deviceMeasurements[k].deviceMeasurementValue+" °C");
                        } else if (deviceMeasurements[k].idMeasurementType == 2) {
                            singleMeasurement.html("L: "+deviceMeasurements[k].deviceMeasurementValue+" lux");
                        } else if (deviceMeasurements[k].idMeasurementType == 3) {
                            singleMeasurement.html("W: "+deviceMeasurements[k].deviceMeasurementValue+"%");
                        } else if (deviceMeasurements[k].idMeasurementType == 4) {
                            singleMeasurement.html("C: "+deviceMeasurements[k].deviceMeasurementValue+" Pa");
                        } else if (deviceMeasurements[k].idMeasurementType == 5) {
                            singleMeasurement.html("RL1: ");
                            if (deviceMeasurements[k].deviceMeasurementValue == 1) singleMeasurement.append("<input type='checkbox' checked onclick='changeState(this,"+deviceMeasurements[k].idDeviceMeasurement+")'>");
                            else singleMeasurement.append("<input type='checkbox' onclick='changeState(this,"+deviceMeasurements[k].idDeviceMeasurement+")'>");
                        } else if (deviceMeasurements[k].idMeasurementType == 6) {
                            singleMeasurement.html("RL2: ");
                            if (deviceMeasurements[k].deviceMeasurementValue == 1) singleMeasurement.append("<input class='' type='checkbox' checked onclick='changeState(this,"+deviceMeasurements[k].idDeviceMeasurement+")'>");
                            else singleMeasurement.append("<input type='checkbox' onclick='changeState(this,"+deviceMeasurements[k].idDeviceMeasurement+")'>");
                        } else if (deviceMeasurements[k].idMeasurementType == 7) {
                            singleMeasurement.html("RL3: ");
                            if (deviceMeasurements[k].deviceMeasurementValue == 1) singleMeasurement.append("<input type='checkbox' checked onclick='changeState(this,"+deviceMeasurements[k].idDeviceMeasurement+")'>");
                            else singleMeasurement.append("<input type='checkbox' onclick='changeState(this,"+deviceMeasurements[k].idDeviceMeasurement+")'>");
                        } else if (deviceMeasurements[k].idMeasurementType == 8) {
                            singleMeasurement.html("RL4: ");
                            if (deviceMeasurements[k].deviceMeasurementValue == 1) singleMeasurement.append("<input type='checkbox' checked onclick='changeState(this,"+deviceMeasurements[k].idDeviceMeasurement+")'>");
                            else singleMeasurement.append("<input type='checkbox' onclick='changeState(this,"+deviceMeasurements[k].idDeviceMeasurement+")'>");
                        } else if (deviceMeasurements[k].idMeasurementType == 9) {
                            singleMeasurement.html("Ostatni ruch: "+deviceMeasurements[k].deviceMeasurementDate)
                        } else if (deviceMeasurements[k].idMeasurementType == 10) { //ROLETA
                            alert("ZROB OBSLUGE FORNTU ROLETY");
                        } else if (deviceMeasurements[k].idMeasurementType == 11) {
                            if (deviceMeasurements[k].deviceMeasurementValue == 1) singleMeasurement.append("<input type='checkbox' checked onclick='changeState(this,"+deviceMeasurements[k].idDeviceMeasurement+")'>");
                            else singleMeasurement.append("<input type='checkbox' onclick='changeState(this,"+deviceMeasurements[k].idDeviceMeasurement+")'>");
                        }
                    }
                } else {
                    measurementList.append("Brak pomiarów.");
                }
            }
        } else {
            deviceGroupBody.append("brak urzadzen");
        }
    }
    var addDeviceGroupButton = $("<button class='btn btn-success section-button'>");
    addDeviceGroupButton.html("Dodaj nową grupę urządzeń");
    addDeviceGroupButton.attr("onclick", "createDeviceGroup()");
    $(deviceSection).append(addDeviceGroupButton);
}

$('body').on('click', '.device-group-title', function (e) {
    var coll = $(this).attr("href");
    $(coll).collapse("toggle");
});