$(document).ready(function() {
    $("#register_submit").on("click", validate);

});

function validate() {
    if(checkForErrors()) 
        return;

    $.ajax({
        url: "index.php?rt=register/validate",
        type: "POST",
        dataType: "json",
        data: {
            username : $("#username").val(),
            password : $("#password").val(),
            email: $("#email").val()
        },
        success : function(data) {
            if(data.register) {
                $("#mid").empty();

                $("#mid").append("<span> <br><br><br>Registracija uspješna! <br><br> Na vašu e-mail adresu je poslan aktivacijski link!<br><br></span>");
                $("#mid").append("<a link href='index.php?rt=login/index'> Povratak na login. </a>");
            }
            else {
                $("#error").html(data.error_message);
            }
        },
        error : function(xhr, status) {
            if( status !== null )
                console.log( "Greška prilikom Ajax poziva: " + status );
        }

    });
}

function checkForErrors() {
    var success = false;
    
    if($("#username").val() == '') {
        $("#username").attr("placeholder", "OBAVEZNO!");
        success = true;
    }

    if($("#password").val() == '') {
        $("#password").attr("placeholder", "OBAVEZNO!");
        success = true;
    }

    if($("#email").val() == '') {
        $("#email").attr("placeholder", "OBAVEZNO!");
        success = true;
    }

    return success;
}