<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 23/01/16
 * Time: 16:20
 */
?>

<div id="flux_choice">
    <span id="twitter_button">Twitter</span>
    <span id="RSS_button" >RSS</span>
    <span id="IMAP_button">IMAP</span>
</div>
<div id="add_flux_body">
    <form class="flux_twitter" action="" method="post">
        <input type="date" name="since_date" placeholder="À partir de" required>
        <input type="text" name="twitter_account" placeholder="Nom du compte @" required>
    </form>

    <form action="flux_rss" action="" method="post">
        <input type="text" name="url_flux" placeholder="URL du flux" required>
    </form>

    <form action="flux_imap" action="" method="post">
        <input type="text" name="host_name" placeholder="Serveur IMAP" required>
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="date" name="since_date" placeholder="À partir de" required>
    </form>
</div>

<script>
    $(function() {
        $('.flux_twitter').show();
        $('.flux_rss').hide();
        $('.flux_imap').hide();

        $('#twitter_button').click(function()
        {
            $('.flux_twitter').show();
            $('.flux_rss').hide();
            $('.flux_imap').hide();
        });
        $('#RSS_button').click(function()
        {
            $('.flux_twitter').hide();
            $('.flux_rss').show();
            $('.flux_imap').hide();
        });
        $('#IMAP_button').click(function()
        {
            $('.flux_twitter').hide();
            $('.flux_rss').hide();
            $('.flux_imap').show();
        });
    });
</script>
