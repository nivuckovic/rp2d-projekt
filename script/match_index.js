var profile;

$(document).ready( function() {
    $.ajax({
        url : 'index.php?rt=profile/getProfile',
        dataType : "json",
        success : function(data) {
            profile = data.profile;
        },
        error : function(xhr, status) {
            if( status !== null )
                console.log( "Greška prilikom Ajax poziva: " + status );
        }

    });

    $("#min_godine_text").html("Min godine: " + $("#min_godine_slider").val());
    $("#max_godine_text").html("Max godine: " + $("#max_godine_slider").val());

    $("#min_godine_slider").on("input", function() {
        if($("#min_godine_slider").val() > $("#max_godine_slider").val()) {
            $("#min_godine_slider").val($("#max_godine_slider").val());
        }

        $("#min_godine_text").html("Min godine: " + $(this).val());
    });

    $("#max_godine_slider").on("input", function() {
        if($("#max_godine_slider").val() < $("#min_godine_slider").val()) {
            $("#max_godine_slider").val($("#min_godine_slider").val());
        }

        $("#max_godine_text").html("Max godine: " + $(this).val());
    });

    $("#match_svi").on("click", prikaziSveProfile);
    $("#match_trazi").on("click", prikaziFiltrirajProfile);

    $("body").on("click", "#match_button", function() {
        var user_2 = $(this).attr("value");

        $.ajax({
            url : "index.php?rt=match/insertNewMatchRequest",
            dataType : 'json',
            type : 'POST',
            data : {
                user_2 : user_2
            },
            success : function(data) {
                
            },
            error : function(xhr, status) {
                if( status !== null )
                    console.log( "Greška prilikom Ajax poziva: " + status );
            }
        });
        
    });
});

function prikaziSveProfile() {
    $.ajax({
        url : "index.php?rt=match/allProfiles",
        dataType : 'json',
        success : function(data) {
            $("#match_profiles_box").empty();
            $("#match_profiles_box").append("<span> Profili: </span>");

            for(i = 0; i < data.profiles.length; ++i) {
                prikaziProfil(data.profiles[i]);
            }
        },
        error : function(xhr, status) {
            if( status !== null )
                console.log( "Greška prilikom Ajax poziva: " + status );
        }
    });
}

function prikaziFiltrirajProfile() {
    $.ajax({
        url : "index.php?rt=match/filterProfiles",
        type : 'POST',
        dataType : 'json',
        data : {
            spol : $('input[name="spol"]:checked').val(),
            min_godine : $("#min_godine_slider").val(),
            max_godine : $("#max_godine_slider").val()
        },
        success : function(data) {
            $("#match_profiles_box").empty();
            $("#match_profiles_box").append("<span> Profili: </span>");

            for(i = 0; i < data.profiles.length; ++i) {
                prikaziProfil(data.profiles[i]);
            }
        },
        error : function(xhr, status) {
            if( status !== null )
                console.log( "Greška prilikom Ajax poziva: " + status );
        }
    });
}

function prikaziProfil(profil) {
    $("#match_profiles_box").append("<div id='" + profil.username + "_box'> </div>");
    $("#" + profil.username + "_box").append('<img src="' + profil.profilna_url + '" width="128" height="128"><br>');
    $("#" + profil.username + "_box").append('<span> Username: ' + profil.username + ' </span><br>');
    $("#" + profil.username + "_box").append('<span> Godine: ' + profil.godine + ' </span><br>');
    $("#" + profil.username + "_box").append('<span> Lokacija: ' + profil.lokacija + ' </span><br>');
    $("#" + profil.username + "_box").append('<span> O meni: ' + profil.o_meni + ' </span><br>');

    $("#" + profil.username + "_box").append('<button id="match_button" value="' + profil.username + '"> Lajkaj me! </button>')
}