var user;
var last_scrollHeight;
var change_scrollHeight;
var start_height;

$(document).ready(function() {
    var url = new URL(window.location.href);
    user = url.searchParams.get("user");

    start_height = $("#chat_box").height(); 

    postaviProfile();
    dohvatiPoruke();

    $("#message_input").on("keypress", function(e) {
        if(e.which == 13) { // Enter key
            if($(this).val() != '') {
                pošaljiPoruku();
                dohvatiPoruke();
            }
        }
    });

    $("#message_submit").on("click", function(e) {
        if($("#message_input").val() != '')
            pošaljiPoruku();
            dohvatiPoruke();
    });

    setInterval(dohvatiPoruke, 1000);
});

function dohvatiPoruke() {
    last_scrollHeight = $("#chat_box").scrollTop() + start_height;

    if(last_scrollHeight == $("#chat_box")[0].scrollHeight) {
        change_scrollHeight = true;
    }
    else {
        change_scrollHeight = false;
    }

    $.ajax({
        url: "index.php?rt=chat/getMessages",
        type: "POST",
        dataType: "json",
        data: {
            user_2 : user
        },
        success: function(data) {
            prikaziPoruke(data);
        },
        error : function(xhr, status) {
            if( status !== null )
                console.log( "Greška prilikom Ajax poziva: " + status );
        }
    });
}

function prikaziPoruke(poruke) {
    var i = 0, j = 0;

    $("#chat_box").empty();

    var height = 0;
    var offset = 8;
    while(i != poruke.user_1.length && j != poruke.user_2.length) {
        if(poruke.user_1[i].vrijeme < poruke.user_2[j].vrijeme) {
            var style = "right: 8px; position: absolute; top:" + String(height + offset) + "px; max-width : 220px";
            var id = "user_1";
            var poruka = poruke.user_1[i].poruka;

            i = i + 1;
        }   
        else {
            var style = "left: 8px; position: absolute; top:" + String(height + offset) + "px; max-width : 220px";
            var id = "user_2";
            var poruka = poruke.user_2[j].poruka;

            j = j + 1;
        } 

        $("#chat_box").append("<div id='" + id + "' style='" + style + "'> " + poruka + " </div>");
        height += $("#" + id + ":last-child").height() + offset;
    }

    while(i < poruke.user_1.length) {
        var style = "right: 8px; position: absolute; top:" + String(height + offset) + "px; max-width : 220px";
        var id = "user_1";
        var poruka = poruke.user_1[i].poruka;

        $("#chat_box").append("<div id='" + id + "' style='" + style + "'> " + poruka + " </div>");
        height += $("#user_1:last-child").height() + offset;

        i = i + 1;
    }

    while(j < poruke.user_2.length) {
        var style = "left: 8px; position: absolute; top:" + String(height + offset) + "px; max-width : 220px";
        var id = "user_2";
        var poruka = poruke.user_2[j].poruka;

        $("#chat_box").append("<div id='" + id + "' style='" + style + "'> " + poruka + " </div>");
        height += $("#user_2:last-child").height() + offset;

        j = j + 1;
    }
    
    if(change_scrollHeight) {
        $("#chat_box").scrollTop($("#chat_box")[0].scrollHeight);
    }
    else {
        $("#chat_box").scrollTop(last_scrollHeight - start_height);
    }
        
}

function pošaljiPoruku() {
    $.ajax({
        url: "index.php?rt=chat/sendMessage",
        dataType: 'json',
        type: 'POST',
        data: {
            user_2: user,
            poruka: $("#message_input").val()
        },
        success : function(data) {

        },
        error : function(xhr, status) {
            if( status !== null )
                console.log( "Greška prilikom Ajax poziva: " + status );
        }
    });

    $("#message_input").val("");
}

function postaviProfile() {

}