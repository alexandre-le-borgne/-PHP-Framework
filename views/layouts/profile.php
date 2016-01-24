<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 24/01/16
 * Time: 02:47
 */
$view->extend('layouts/layout');
$this->render('persists/header');

//var_dump($categories);
//Pour pas reecrire plein de fois
function formTr($string, $streams, $streamType)
{
?>
    <tr>
        <td>
            <?= 'Nom du flux (' . $string . ')'; ?>
        </td>
        <td>
            Action
        </td>
    </tr>
    <?php
    foreach($streams as $stream)
    {
        ?>
        <tr>
            <td>
                <b><?= $stream->toString() ?></b>
            </td>
            <td>
                <form action="<?= View::getUrlFromRoute('deletestream') ?>" method="post">
                    <input type="hidden" name="id" value="<?= $stream->getId() ?>">
                    <input type="hidden" name="streamType" value="<?= $streamType ?>">
                    <input type="submit" value="Supprimer" name="delStream">
                </form>
            </td>
        </tr>
    <?php
    }

}

?>
<div class="user_section">
    <div id="header">
        <h1><?= $profile ?></h1>
    </div>
    <nav>
        <div id="stream">Mes Flux</div>
        <div id="followers_button">Mes Abonnés</div>
        <div id="following_botton">Mes Abonnements</div>
    </nav>
    <div id="categories">
        <?php
            if (isset($categories))
            {
                if (empty($categories))
                { ?>
                <div>

                </div>
                <?php
                }
                else
                {
                ?>
                    <div class="wrapper">
                    <h1>Gestion des flux</h1>
                <?php
                }

                foreach ($categories as $category)
                {
                    $cat = $category['categories'];
                    ?>
                    <table border = 1>
                    <tr bgcolor = #DDD>
                        <td>
                            <b><?= 'Categorie : ' . $category['title'] ?></b>
                        </td>
                        <td>
                            <form action="<?= View::getUrlFromRoute('deletecategory') ?>" method="post">
                                <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                <input type="submit" value="Supprimer" name="delCat">
                            </form>
                        </td>
                    </tr>
                    <?php
                    if (!(empty($cat['twitter'])))
                        formTr('Twitter', $cat['twitter'], ArticleModel::TWITTER);
                    if (!(empty($cat['email'])))
                        formTr('Email', $cat['email'], ArticleModel::EMAIL);
                    if (!(empty($cat['rss'])))
                        formTr('RSS', $cat['rss'], ArticleModel::RSS);
                    ?>
                    </table>
                    <?php
                }
                echo '</div>';
            }
        ?>
    </div>

    <div id="following">
        <?php
        if (isset($following) && !empty($following))
        {
            echo '<div class="wrapper"><h1>Les gens que je suit</h1>';
            ?>
            <table border = 1>
                <tr bgcolor = #DDD>
                    <td>
                        <b>Utilisateur</b>
                    </td>
                    <td>
                        <b>Action</b>
                    </td>
                </tr>
            <?php
            foreach ($following as $follow)
            { ?>
            <tr bgcolor = #EEE>
                <td>
                    <b><?= $follow->username ?></b>
                </td>
                <td>
                    <form action="<?= View::getUrlFromRoute('unfollow') ?>" method="post">
                        <input type="hidden" name="id" value="<?= $follow->getId() ?>">
                        <input type="submit" value="Arrêter de le suivre">
                    </form>
                </td>
            </tr>
            <?php }

            echo '</table></div>';
        } ?>
    </div>

    <div id="followers">
        <?php
        if (isset($followers) && !empty($followers))
            {
            $i = 0;
            echo '<div class="wrapper"><h1>Les gens qui me suivent</h1>';

                foreach ($followers as $follower)
                {
                echo $follower->username . ', ';
                ++$i;
                if ($i == 8)
                {
                $i = 0;
                echo '<br/>';
                }
                }
                echo '</div>';
            }
        ?>
    </div>
</div> <!-- USER_CONTENT-->

<?php


/*
echo "<table border = 1> <tr bgcolor = #DDD>";
echo "<td>id</td><td>Pseudonyme</td><td>Action</td></tr>";

foreach ($users as $user):
{
    if (!($user instanceof UserEntity)) continue;
    echo '<tr bgcolor=' . '#EEE' . '>';
    echo "<td>" . $user->getId() . "</td>";
    echo "<td>" . $user->getUsername() . "</td>";
    ?>
    <td>
        <form action="<?= View::getUrlFromRoute('deleteuser') ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $user->getId(); ?>">
            <input type="submit" value="Supprimer" name="delUser">
            <input type="submit" value="Voir le profil" name="seeBlog">
        </form>
    </td>

    <?php
    echo '</tr>';
}
endforeach;

echo '</table></div>';
}




foreach ($categories as $category)
{
    echo $category['title'];
    echo '<br/>';

    $cat = $category['categories'];
    var_dump($cat['twitter']);
    var_dump($cat['email']);
    var_dump($cat['rss']);

    echo '<br/>';
}



if (isset($deleted))
{?>
<div id="text_cat" >
    <?= $deleted ?>
</div>
<?php
}

if (isset($error))
{?>
<div id="text_cat" >
    <?= $error ?>
</div>
<?php
}



pour les longues pages Web, un ancrage permettant remonter en haut de la page ;

 les balises meta description et keywords devront être renseignées (sur chaque page

Web) ;

 un favicon (contenant plusieurs images de différent formats pour un affichage optimal en fonction de la taille et du nombre de couleurs supporté par le système d’exploitation) doit apparaitre sur l’ensemble des pages Web ;

 utilisation « correcte » de HTML, CSS, JavaScript et PHP ;
 bonne indentation de l’ensemble des codes sources ;
 bonne architecture des répertoires sources ;
 validationW3C de toutes vos pages HTML (documents au minimum de type HTML5) ;
 validation W3C de toutes vos pages CSS (documents au minimum de profil CSS niveau

2.1 avec aucun avertissement et en tenant compte des extensions propriétaires comme avertissement)






*/
