$(document).ready(function() {
    $("#odlogiraj_se").on("click", function() {
        // Iz nekog razloga ne radi
        // window.location.href = "index.php?rt=login/logout";
        $.ajax({
            url : "index.php?rt=login/logout",
            dataType : "json",
            success: function(data) {
                window.location.href = "index.php?rt=login/index";
            },
            error : function(xhr, status) {
                if( status !== null )
                    console.log( "Greška prilikom Ajax poziva: " + status );
            }
        });
    })

});