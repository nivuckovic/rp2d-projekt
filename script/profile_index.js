var profile;

$(document).ready( function() {
    $.ajax({
        url : 'index.php?rt=profile/getProfile',
        dataType : 'JSON',
        success : function(data) {
            profile = data.profile;
            
            $("#slika").append('<img src="' + profile.profilna_url + '" width="128" height="128">')
            $("#username").html(profile.username);
        },
        error : function(data) {
            if(status != null)
                console.log( "Greška prilikom Ajax poziva: " + status );
        }
    });

    $("#izmjeni_profil").on("click", function() {
        window.location.href = "index.php?rt=profile/create";
    });
});