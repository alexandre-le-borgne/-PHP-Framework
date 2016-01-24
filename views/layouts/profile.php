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
function formTr($string, $streams)
{
?>
    <tr bgcolor="#DDD">
        <td>
            <?= 'Nom du flux ' . $string; ?>
        </td>
        <td>
            Action
        </td>
    </tr>
    <?php
    foreach($streams as $stream)
    {
        ?>
        <tr bgcolor="#EEE">
            <td>
                <b><?= $stream->toString() ?></b>
            </td>
            <td>
                <form action="<?= View::getUrlFromRoute('deletestream') ?>" method="post">
                    <input type="hidden" name="id" value="<?= $stream->getId() ?>">
                    <input type="submit" value="Supprimer" name="delStream">
                    <input type="submit" value="Enlever de la catégorie" name="removeFromCat">
                </form>
            </td>
        </tr>
    <?php
    }
}

if (isset($categories))
{
    ?>
        <div class="wrapper">
        <h1>Gestion des flux</h1>
    <?php

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
                    <input type="submit" value="Voir la categorie" name="seeCat">
                </form>
            </td>
        </tr>
        <?php
        if (!(empty($cat['twitter'])))
            formTr('Twitter', $cat['twitter']);
        if (!(empty($cat['email'])))
            formTr('Email', $cat['email']);
        if (!(empty($cat['rss'])))
            formTr('RSS', $cat['rss']);
        ?>
        </table>
        <?php

        $cat = $category['categories'];
        var_dump($cat['twitter']);
        var_dump($cat['email']);
        var_dump($cat['rss']);

        echo '<br/>';
    }
}

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
*/
