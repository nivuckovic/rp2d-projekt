$(document).ready(function() {
    $("#login_submit").on("click", Login);
    $("#register_submit").on("click", function() { window.location.href = "index.php?rt=register/index" });
});

function Login() {
    $.ajax({
        url : 'index.php?rt=login/validateLogin',
        type : 'POST',
        dataType : 'json',
        data : {
            username : $("#username").val(),
            password : $("#password").val()
        },
        success : function(data) {
            if(data.login) {
                window.location.href = data.location;
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