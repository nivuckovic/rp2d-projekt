var slike_url_muski= ["https://cdn.iconscout.com/icon/free/png-512/avatar-372-456324.png", "https://cdn.iconscout.com/icon/free/png-512/avatar-380-456332.png",
"https://cdn.iconscout.com/icon/free/png-256/avatar-371-456323.png"];

var slike_url_zenski = ["https://cdn.iconscout.com/icon/free/png-512/avatar-369-456321.png", 
"https://cdn.iconscout.com/icon/free/png-512/avatar-377-456329.png", "https://cdn1.iconfinder.com/data/icons/user-pictures/100/female1-512.png"]

$(document).ready(function() {
    setProfilePicturesOnStart(slike_url_zenski, "zensko");
    onGenderChange();
    onSaveChanges();

    checkIfProfileCreated();
});

function onGenderChange() {
    $("#musko").on("change", function() {
        $("#slike_box").empty();

        for(i = 0; i < slike_url_muski.length; ++i) {
            $("#slike_box").append('<input type="radio" id="musko_' + String(i) + '" name="profile_pic" value="' + slike_url_muski[i] + '">');
            $("#slike_box").append('<img src="' + slike_url_muski[i] + '" width="128" height="128">');
        }

        $("#musko_0").prop("checked", true);
    });
    
    $("#zensko").on("change", function() {
        $("#slike_box").empty();

        for(i = 0; i < slike_url_zenski.length; ++i) {
            $("#slike_box").append('<input type="radio" id="zensko_' + String(i) + '" name="profile_pic" value="' + slike_url_zenski[i] + '">');
            $("#slike_box").append('<label for="zensko_' + String(i) + '"><img src="' + slike_url_zenski[i] + '" width="128" height="128"></label>');
        }
    
        $("#zensko_0").prop("checked", true);
    });
}

function setProfilePicturesOnStart(container, gender) {
    $("#slike_box").empty();

    for(i = 0; i < container.length; ++i) {
        $("#slike_box").append('<input type="radio" id="' + gender + '_' + String(i) + '" name="profile_pic" value="' + container[i] + '">');
        $("#slike_box").append('<img src="' + container[i] + '" width="128" height="128">');
    }

    $("#zensko_0").prop("checked", true);
}

function onSaveChanges() {
    $("#spremi_profil").on("click", function() {
        if(checkForErrors())
            return;

        $.ajax({
            url : 'index.php?rt=profile/saveChanges',
            type : 'POST',
            dataType : 'json',
            data : {
                ime : $("#ime").val(),
                prezime : $("#prezime").val(),
                godine : $("#godine").val(),
                spol : $('input[name="spol"]:checked').val(),
                slika : $('input[name="profile_pic"]:checked').val(),
                lokacija : $("#lokacija").val(),
                o_meni : $("#o_meni").val()
            },
            success : function(data) {
                window.location.href = data.location;
            },
            error : function(xhr, status) {
                if( status !== null )
                    console.log( "Greška prilikom Ajax poziva: " + status );
            }
        });
    });
}

function checkIfProfileCreated() {
    $.ajax({
        url : 'index.php?rt=profile/profileCreated',
        dataType : 'JSON',
        success : function(data) {
            if(data.success) {
                $("#ime").val(data.user.ime);
                $("#prezime").val(data.user.prezime);
                $("#godine").val(data.user.godine);
                $("#" + data.user.spol).attr("checked", true);
                
                if(data.user.spol == "musko") {
                    setProfilePicturesOnStart(slike_url_muski, "musko");
                }
                else {
                    setProfilePicturesOnStart(slike_url_zenski, "zensko");
                }

                $("input[value='" + data.user.profilna_url + "']").attr("checked", true);

                $("#lokacija").val(data.user.lokacija);
                $("#o_meni").val(data.user.o_meni);
            }
        },
        error : function(xhr, status) {
            if( status !== null )
                console.log( "Greška prilikom Ajax poziva: " + status );
        }

    })
}

function checkForErrors() {
    var success = false;
    
    if($("#ime").val() == '') {
        $("#ime").attr("placeholder", "OBAVEZNO!");
        success = true;
    }

    if($("#prezime").val() == '') {
        $("#prezime").attr("placeholder", "OBAVEZNO!");
        success = true;
    }

    if($("#godine").val() == '') {
        $("#godine").attr("placeholder", "OBAVEZNO!");
        success = true;
    }

    if($("#lokacija").val() == '') {
        $("#lokacija").attr("placeholder", "OBAVEZNO!");
        success = true;
    }

    if($("#o_meni").val().length >= 140) {
        $("#o_meni").val("Najviše 140 slova!");
        success = true;
    }

    return success;
}