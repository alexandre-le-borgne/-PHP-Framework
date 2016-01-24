<?php
$view->extend('layouts/layout');
?>
<div id="fullpage">

    <?php
    echo $_content;
    $this->render('layouts/section_news');
    $this->render('layouts/section_content');
    $this->render('layouts/section_social');
    $this->render('layouts/section_contact');
    ?>
</div>
<script>

    $(document).ready(function() {
        $('#fullpage').fullpage();

        function centrerElementAbsolu(element)
        {
            var largeur_fenetre = $(window).width();
            var hauteur_fenetre = $(window).height();

            var haut = (hauteur_fenetre - $(element).height()) / 2 + $(window).scrollTop();
            var gauche = (largeur_fenetre - $(element).width()) / 2 + $(window).scrollLeft();
            $(element).css({position: 'absolute', top: haut, left: gauche});
        }

        centrerElementAbsolu('.loginDiv');
        centrerElementAbsolu('.registerDiv');

        $(window).resize(function() {
            centrerElementAbsolu('.loginDiv');
            centrerElementAbsolu('.registerDiv');
        });

        $(document).ready( function () {
            // Add return on top button
            $('body').append('<div id="returnOnTop" title="Retour en haut">&nbsp;</div>');

            // On button click, let's scroll up to top
            $('#returnOnTop').click( function() {
                $('html,body').animate({scrollTop: 0}, 'slow');
            });
        });

        $(window).scroll(function() {
            // If on top fade the bouton out, else fade it in
            if ( $(window).scrollTop() == 0 )
                $('#returnOnTop').fadeOut();
            else
                $('#returnOnTop').fadeIn();
        });
    });
</script>