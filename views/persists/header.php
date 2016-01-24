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
            <script>
                $(function() {
                    var availableTags = [];
                    $(".search_bar").keyup(function() {
                        var channel = $(this).val();
                        $.ajax({
                            method: "POST",
                            url: "<?= View::getUrlFromRoute('ajax') ?>",
                            dataType: 'json',
                            data: {
                                action: 'search',
                                channel: channel
                            },
                            success: function(result) {
                                if(Array.isArray(result)) {
                                    availableTags = result;
                                    $( ".search_bar" ).autocomplete('option', 'source', availableTags);
                                }
                            }
                        });
                    }).autocomplete({
                        source: function() { return availableTags; },
                        'open': function(e, ui) {
                            $('.ui-autocomplete').css('top', $("ul.ui-autocomplete").cssUnit('top')[0] + 4);
                            $('.ui-autocomplete').css('left', $("ul.ui-autocomplete").cssUnit('left')[0] - 2);
                            $('.ui-autocomplete').append("<div id='autofill_results'><span>2,000</span> results found, showing <span>10</span></div>");
                        }
                    });
                });
            </script>
            <?php
            $this->render('forms/logoutForm');
            ?>
            <a id="add_flux" href="<?= View::getUrlFromRoute('addstream')?>"><img src="<?= View::getAsset('img/add_cat.png') ?>" width="30"></a>
            <a id="profile" href="<?= View::getUrlFromRoute('profile')?>"><img src="<?= View::getAsset('img/profil.png') ?>" width=""></a>
            <a id="home" href="<?= View::getUrlFromRoute('index')?>"><img src="<?= View::getAsset('img/home.png') ?>" width=""></a><?php
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
</header>
