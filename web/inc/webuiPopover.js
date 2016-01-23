function addImageForm(elm) {
    $(".webui-popover-title", 
        $(elm).parent().html(
            '<input type="text" id="imageTitle" placeholder="Titre">\
            <input type="text" id="imageLink" placeholder="Lien vers l\'image...">\
            <input type="button" onclick="addImage($(\'#imageTitle\', $(this).parent()).val(), $(\'#imageLink\', $(this).parent()).val())" value="Ajouter">'
        ).parent().parent()
    ).html(
        'Ajouter une image'
    );
}

function addVideoForm(elm) {
    $(".webui-popover-title", 
        $(elm).parent().html(
            '<input type="text" id="videoTitle" placeholder="Titre">\
            <input type="text" id="videoLink" placeholder="Code d\'intégration...">\
            <input type="button" onclick="addVideo($(\'#videoTitle\', $(this).parent()).val(), $(\'#videoLink\', $(this).parent()).val())" value="Ajouter">'
        ).parent().parent()
    ).html(
        'Ajouter une video'
    );
}

function addLienForm(elm) {
    $(".webui-popover-title", 
        $(elm).parent().html(
            '<input type="text" id="lienTitle" placeholder="Titre">\
            <input type="text" id="lienLink" placeholder="Lien...">\
            <input type="button" onclick="addLien($(\'#lienTitle\', $(this).parent()).val(), $(\'#lienLink\', $(this).parent()).val())" value="Ajouter">'
        ).parent().parent()
    ).html(
        'Ajouter un lien'
    );
}

$(function() {
    var visibility = $("fieldset #actualvisibility").html();  
    var sharelink = $("fieldset #sharelink").html();
    var changelink = $("fieldset #changelink").html();
    
    $("#share").webuiPopover({
        title:'Partager',
        content: '\
            <div id="popoverOptions">\n\
                <input type="text" value="'+sharelink+'">\n\
            </div>\n\
        ',
        closeable:true,
        trigger:'hover',
        animation:'pop'
    });

    $("#link").webuiPopover({
        title:'Changer de lien',
        content: '\
            <div id="popoverOptions">\n\
                <form method="post">\n\
                    <input type="text" name="linkedit" value="'+changelink+'">\n\
                    <input type="submit" value="Modifier le lien">\n\
                </form>\n\
            </div>\n\
        ',
        closeable:true,
        trigger:'hover',
        animation:'pop'
    });

    $('#visibility').webuiPopover({
        title:'Changer de visibilité',
        content: function () {
            if(visibility == "public") {
                return '\
                    <div id="popoverOptions">\n\
                        <form method="post">\n\
                            <input style="display:none">\n\
                            <input type="password" style="display:none">\n\
                            <input required autofocus autocomplete="off" name="passwordadd" placeholder="Mot de passe" type="password">\n\
                            <input type="submit" value="Ajouter un mot de passe">\n\
                        </form>\n\
                    </div>\n\
                ';
            }
            else {
                return '\
                    <div id="popoverOptions">\n\
                        <form method="post">\n\
                            <input type="submit" name="passworddel" value="Supprimer le mot de passe">\n\
                        </form>\n\
                    </div>\n\
                ';
            }
        },
        closeable:true,
        trigger:'hover',
        animation:'pop'
    });

    $('#add').webuiPopover({
        title:'Créer un nouveau post-it',
        content:'\
            <div id="popoverOptions">\n\
                <a id="addnote" href="#">\n\
                    <img src="img/note.png">\n\
                    Créer une note\n\
                </a>\n\
                <a href="#" onclick="addImageForm(this)">\n\
                    <img src="img/photo.png">\n\
                        Insérer une image\n\
                </a>\n\
                <a href="#" onclick="addVideoForm(this)">\n\
                    <img src="img/film.png">\n\
                    Insérer une video\n\
                </a>\n\
                <a href="#" onclick="addLienForm(this)">\n\
                    <img src="img/filelink.png">\n\
                    Insérer un lien\n\
                </a>\n\
            </div>',
        cache:false,
        closeable:true,
        trigger:'hover',
        animation:'pop'
    });
});