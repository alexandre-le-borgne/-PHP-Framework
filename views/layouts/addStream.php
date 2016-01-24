<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 23/01/16
 * Time: 16:20
 */

$view->extend('layouts/layout');
$this->render('persists/header');

if(isset($errors)) {
    foreach ($errors as $error)
    {
        echo '<div class="errors_fields">'.$error.'</div>';
    }
}
?>

<div id="add_stream_section"
    <div id="flux_choice">
        <div id="twitter_button"><img src="<?= View::getAsset('img/twitter.png') ?>" width="27"></div>
        <div id="RSS_button" ><img src="<?= View::getAsset('img/rss.png') ?>" width="27"></div>
        <div id="IMAP_button"><img src="<?= View::getAsset('img/email.png') ?>" width="27"></div>
    </div>

    <div id="add_flux_body">
        <form class="flux_twitter" action="<?= View::getUrlFromRoute('addtwitterstream')?>" method="post">
            <!--<input class="first" type="date" name="firstUpdate" placeholder="À partir de" required>-->
            <input placeholder="À partir de" class="first" type="text" onfocus="(this.type='date')" name="firstUpdate">
            <input type="text" name="channel" placeholder="Nom du compte @" required>
            <input class="last" type="text" name="category" placeholder="La catégorie pour le ranger" required>
            <input type="submit" name="button" value="Ajouter ce flux !">
        </form>

        <form class="flux_rss" action="<?= View::getUrlFromRoute('addrssstream')?>" method="post">
            <input class="first" type="date" name="firstUpdate" placeholder="À partir de" required>
            <input type="text" name="url_flux" placeholder="URL du flux" required>
            <input class="last" type="text" name="category" placeholder="La catégorie pour le ranger" required>
            <input type="submit" name="button" value="Ajouter ce flux !">
        </form>

        <form class="flux_imap" action="<?= View::getUrlFromRoute('addemailstream') ?>" method="post">
            <input type="date" name="firstUpdate" placeholder="À partir de" required>
            <input class="first" type="text" name="server" placeholder="Serveur IMAP" required>
            <input type="text" name="user" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="number" name="port" placeholder="Port du serveur" required>
            <input class="last" type="text" name="category" placeholder="La catégorie pour le ranger" required>
            <input type="submit" name="button" value="Ajouter ce flux !">
        </form>
    </div>
</div>

<script>
    $(function() {
        $('.flux_twitter').show();
        $('.flux_rss').hide();
        $('.flux_imap').hide();
        $('#twitter_button').css('background', '#529ecc');

        $('#twitter_button').click(function()
        {
            $('#twitter_button').css('background', '#529ecc');
            $('#RSS_button').css('background', '#2f3d51');
            $('#IMAP_button').css('background', '#2f3d51');

            $('.flux_twitter').show();
            $('.flux_rss').hide();
            $('.flux_imap').hide();
        });
        $('#RSS_button').click(function()
        {
            $('#RSS_button').css('background', '#56bc8a');
            $('#twitter_button').css('background', '#2f3d51');
            $('#IMAP_button').css('background', '#2f3d51');

            $('.flux_twitter').hide();
            $('.flux_rss').show();
            $('.flux_imap').hide();
        });
        $('#IMAP_button').click(function()
        {
            $('#IMAP_button').css('background', '#a77dc2');
            $('#twitter_button').css('background', '#2f3d51');
            $('#RSS_button').css('background', '#2f3d51');

            $('.flux_twitter').hide();
            $('.flux_rss').hide();
            $('.flux_imap').show();
        });
    });
</script>


