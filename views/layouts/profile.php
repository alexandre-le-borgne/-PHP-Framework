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


if (isset($categories))
{
    ?>
        <div class="wrapper">
        <h1>Gestion des flux</h1>
    <?php

    foreach ($categories as $category)
    {
        echo '<b>' . $category['title'] . '</b> : ';
        ?>
        <form action="<?= View::getUrlFromRoute('deletecategory') ?>" method="post">
            <input type="hidden" name="id" value="<?= $category['id'] ?>">
            <input type="submit" value="Supprimer" name="delCat">
            <input type="submit" value="Voir la categorie" name="seeCat">
        </form>
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

