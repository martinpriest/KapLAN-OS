function createSceneGroup() {
    var sceneGroupName = prompt("Wprowadz nazwe nowej grupy scen", "");

    var data = {
        "sceneGroupName" : sceneGroupName,
    };

    if(sceneGroupName != null ) {
        $.ajax({
            type: "POST",
            async: false,
            url: 'api/action/scenePanel/createSceneGroup.php',
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

function getSceneGroups() {
    var json = null;
    $.ajax({
        type: "POST",
        async: false,
        url: 'api/action/scenePanel/getSceneGroups.php',
        success: function(response)
        {
            json = response.sceneGroups;
        }
    });
    return json;
}

function getScenes() {

}

function createScene(id) {
    $.get("templates/modals/addSceneModal.html", function(data){
        $(".container").append(data);
        $('#addSceneGroup').modal('show');

        $('#addSceneGroup').on('hidden.bs.modal', function () {
            $(this).remove();
        })



        $("button[name*='addSceneGroupButton']").attr("onclick", "addScene('"+id+"')");
    });
}

function addScene() {

}

function fillScenePanel() {
    var sceneSection = $("#scene-panel");
    var sceneGroups = getSceneGroups();
    if(sceneGroups) {
        var countSceneGroups = sceneGroups.length;
        for(var i=0; i<countSceneGroups; i++) {
            const { idSceneGroup, sceneGroupName } = sceneGroups[i];
            var sceneGroupContainer = $("<div class='scene-group-container'>")
                                    .appendTo(sceneSection);
            var sceneGroupHeader = $("<div class='scene-group-header'>")
                                    .appendTo(sceneGroupContainer);
            var settingSceneGroup = $("<button class='button-icon'>")
                                    .append("<img src='img/icons/settings.png'>")
                                    .attr("onclick", `sceneGroupSetting(${idSceneGroup})`)
                                    .appendTo(sceneGroupHeader);
            var sceneGroupTitle = $("<a data-toggle='collapse' class='scene-group-title'>")
                                    .html(sceneGroupName)
                                    .attr('href', `#${idSceneGroup}s`)
                                    .appendTo(sceneGroupHeader);
            var deleteSceneGroup = $("<button class='button-icon'>")
                                    .append("<img src='img/icons/delete.png'>")
                                    .attr("onclick", `deleteSceneGroup(${idSceneGroup})`)
                                    .appendTo(sceneGroupHeader);
            var sceneGroupBody = $("<div class='scene-group-body collapse'>")
                                    .attr("id", `${idSceneGroup}s`)
                                    .appendTo(sceneGroupContainer);
            
            var scenes = getScenes();
            if(scenes) {
                countScene
            } else {
                sceneGroupBody.append("Brak grup scen do wyświetlenia");
            }
            var addSceneButton = $("<button class='btn btn-success section-button'>")
                                    .attr("onclick", `createScene(${idSceneGroup})`)
                                    .html("Stwórz nową scenę")
                                    .appendTo(sceneGroupBody);
        }
    } else {
        sceneSection.append("Brak grup scen do wyświetlenia");
    }
    var addSceneGroupButton = $("<button class='btn btn-success section-button' onclick='createSceneGroup()'>Dodaj grupę scen</button>");
    sceneSection.append(addSceneGroupButton);
}

$('body').on('click', '.scene-group-title', function (e) {
    var coll = $(this).attr("href");
    $(coll).collapse("toggle");
});