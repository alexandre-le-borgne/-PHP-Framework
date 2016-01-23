<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 23/01/16
 * Time: 16:20
 */
?>
<div class="webui-popover-content">
    <div id="flux_choice">
        <div id="twitter_button">Twitter</div>
        <div id="RSS_button" >RSS</div>
        <div id="IMAP_button">IMAP</div>
    </div>
    <div id="add_flux_body">
        <form class="flux_twitter" action="" method="post">
            <input type="date" name="since_date" placeholder="À partir de" required>
            <input type="text" name="twitter_account" placeholder="Nom du compte @" required>
        </form>

        <form class="flux_rss" action="" method="post">
            <input type="text" name="url_flux" placeholder="URL du flux" required>
        </form>

        <form class="flux_imap" action="" method="post">
            <input type="text" name="host_name" placeholder="Serveur IMAP" required>
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="date" name="since_date" placeholder="À partir de" required>
        </form>
    </div>
</div>


