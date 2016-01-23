<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 19/01/16
 * Time: 16:59
 * Ceci est une vue, donc on peut utiliser $this->uneFonction()
 * @var View $this
 */
?>

<?php
if (isset($mailValidationMessage))
{
    ?>
    <div id="conf_pwd">
        <p><?= $mailValidationMessage ?></p>
    </div>
    <?php
}
?>

<header>
    <nav>
        <?php
        if ($this->isGranted(Session::USER_IS_CONNECTED)) {
            ?>
            <?php
            if ($this->isGranted(Session::USER_IS_ADMIN)) {
                ?>
                <a href="<?= View::getUrlFromRoute('admin') ?>">Panel administrateur</a>
                <?php
            }
            ?>
            <a id="logo_home" href="<?= View::getUrlFromRoute('index') ?>"><img id="logo_aaron_home" src="<?= View::getAsset('img/logo-aaron.png') ?>"></a>
            <form method="post" action="<?= View::getUrlFromRoute('search') ?>">
                <img src="<?= View::getAsset('img/search_logo.png') ?>">
                <input class="search_bar" type="textarea" name="search" placeholder="Recherchez sur Aaron">
                <input type="submit" style="display: none">
            </form>
            <?php
            $this->render('forms/logoutForm');
            ?>
            <div id="add_flux"><img src="<?= View::getAsset('img/add_cat.png') ?>" width="30"><p id="text_cat">Ajouter un flux !</p></div>
            <a id="profile" href="<?= View::getUrlFromRoute('profil')?>">Profil</a>
        <?php
        } else {
            ?>
            <form method="post" action="<?= View::getUrlFromRoute('search') ?>">
                <input type="textarea" name="search" placeholder="Recherchez sur Aaron">
                <input type="submit" style="display: none">
            </form>

            <a href="<?= View::getUrlFromRoute('login') ?>">Se connecter</a>
            <br>
            <a href="<?= View::getUrlFromRoute('register') ?>"">S'enregistrer</a>
            <br>
            <?php
        }
        ?>
    </nav>
    <div class="webui-popover-content">

    </div>
</header>

<script>
    $(function() {
        $('#add_flux').webuiPopover({
        width:300,
        height:200,
        placement:'bottom',
        trigger:'click',
        animation:'pop',
        arrow:true
        });

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
