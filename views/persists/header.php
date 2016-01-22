<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 19/01/16
 * Time: 16:59
 */
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
            <a id="logo_home" href="<? View::getUrlFromRoute('index') ?>"><img id="logo_aaron_home" src="web/img/logo-aaron.png"></a>
            <form method="post" action="search">
                <input class="search_bar" type="textarea" name="search" placeholder="Votre recherche...">
                <input type="submit" style="display: none">
            </form>
            <a id="profile" href="<? View::getUrlFromRoute('profil')?>">Profil</a>
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
    </nav>
</header>
