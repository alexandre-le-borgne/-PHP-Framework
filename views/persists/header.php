<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 19/01/16
 * Time: 16:59
 */
?>

<div id="conf_pwd">
    <p>N'oubliez pas de valider votre inscription avec le mail que nous avons envoyé !</p>
</div>
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
            <?php
            $this->render('forms/logoutForm');
            ?>
            <a id="add_flux" href="<? View::getUrlFromRoute('addflux')?>"><img src="web/img/add_cat.png" width="30"></a>
            <a id="profile" href="<? View::getUrlFromRoute('profil')?>">Profil</a>
        <?php
        } else {
            ?>
            <form method="post" action="search">
                <input type="textarea" name="search" placeholder="Recherchez sur Aaron">
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
