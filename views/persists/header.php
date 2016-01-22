<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 19/01/16
 * Time: 16:59
 */
?>


<header>
    <?php
    if ($this->isGranted(Session::USER_IS_CONNECTED)) {
        ?>
        <div id="profile">
            <a href="<? View::getUrlFromRoute('profil')?>">Profil</a>
        </div>
        <?php
        if ($this->isGranted(Session::USER_IS_ADMIN)) {
            ?>
            <a href="<?= View::getUrlFromRoute('admin') ?>">Panel administrateur</a>
            <?php
        }
        ?>
        <a href="<? View::getUrlFromRoute('index') ?>">Accueil</a>
        <form method="post" action="search">
            <input class="search_bar" type="textarea" name="search" placeholder="Votre recherche...">
            <input type="submit" style="display: none">
        </form>
        <a href="<? View::getUrlFromRoute('streamadd') ?>">Ajouter un flux</a>
        <?php
        $this->render('forms/logoutForm');
    } else {
        ?>
        <form method="post" action="search">
            <input type="textarea" name="search" placeholder="Votre recherche...">
            <input type="submit" style="display: none">
        </form>

        <a href="<? View::getUrlFromRoute('login') ?>">Se connecter</a>
        <br>
        <a href="<? View::getUrlFromRoute('register') ?>"">S'enregistrer</a>
        <br>
        <?php
    }
    ?>
</header>
