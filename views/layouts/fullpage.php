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
            $(element).css({position: 'absolue', top: haut, left: gauche});
        }

        centrerElementAbsolu('.loginDiv');
        centrerElementAbsolu('.registerDiv');
    });
</script>