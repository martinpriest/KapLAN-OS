$.ajax({
    url: 'api/action/checkLogin.php',
    type: 'post',
    dataType: 'json',
    statusCode: {
        200: function(response) {
            // alert(response.userType);
            $.get("templates/before-login/logo.html", function(data){
                $("body").prepend(data).delay(1000);
            });
            //PRZYCISK WYLOGUJ
            var logoutButton = $("<button onclick='logoutUser()' class='btn btn-primary section-button'><span></span>Wyloguj</button>");
            
            //PANEL URZADZEN
            $.get("templates/after-login/devicePanel.html", function(data){
                $(".container").append(data);
                fillDevicePanel();
            });
            //PANEL SCEN
            $.get("templates/after-login/scenePanel.html", function(data){
                $(".container").append(data);
                fillScenePanel();
            });
            //PANEL UZYTKOWNIKA
            $.get("templates/after-login/userPanel.html", function(data){
                $(".container").append(data);
                fillUserPanel();
            });
            //PANEL RODZINY
            if(response.userType == 1) {
                $.get("templates/after-login/familyPanel.html", function(data){
                    $(".container").append(data);
                    fillFamilyPanel();
                });
            }
            //PRZYCISK WYLOGUJ
            $(".container").append(logoutButton);
        },
        400: function(response) {
            //alert(response.responseJSON.message);
            $.get("templates/before-login/logo.html", function(data){
                $("body").prepend(data).delay(1000);
            });

            $.get("templates/before-login/loginForm.html", function(data){
                $(".container").append(data).delay(1000);
            });

            $.get("templates/before-login/registerForm.html", function(data){
                $(".container").append(data).delay(1000);
            });
        }
    }
});
