$(document).ready(function() {
    $.ajax({
        url : 'index.php?rt=match/getMatches',
        dataType : 'json',
        success : function(data) {
            
            for(i = 0; i < data.matches.length; ++i) {
                prikaziProfil(data.matches[i]);
            }
        },
        error : function(xhr, status) {
            if( status !== null )
                console.log( "Greška prilikom Ajax poziva: " + status );
        }

    });

    $("body").on("click", "#chat_button", function() {
        window.location.href = "index.php?rt=chat/index&user=" + $(this).val();
    });

    $("body").on("click", "#unmatch_button", function() {
        $.ajax({
            url: "index.php?rt=match/unmatch",
            type: "POST",
            dataType: "json",
            data: {
                user_2: $(this).val()
            },
            success : function(data) {

            },
            error : function(xhr, status) {
                if( status !== null )
                    console.log( "Greška prilikom Ajax poziva: " + status );
            }
        });
        
        $("#" + $(this).val() + "_box").remove();
    });
});

function prikaziProfil(profil) {
    $("#matches_box").append("<div id='" + profil.username + "_box'> </div>");
    $("#" + profil.username + "_box").append('<img src="' + profil.profilna_url + '" width="128" height="128"><br>');
    $("#" + profil.username + "_box").append('<span> Username: ' + profil.username + ' </span><br>');
    $("#" + profil.username + "_box").append('<span> Godine: ' + profil.godine + ' </span><br>');
    $("#" + profil.username + "_box").append('<span> Lokacija: ' + profil.lokacija + ' </span><br>');
    $("#" + profil.username + "_box").append('<span> O meni: ' + profil.o_meni + ' </span><br>');
    
    $("#" + profil.username + "_box").append('<button id="chat_button" value="' + profil.username + '"> Javi se! </button>');
    $("#" + profil.username + "_box").append('<button id="unmatch_button" value="' + profil.username + '"> Blokiraj! </button>');
}