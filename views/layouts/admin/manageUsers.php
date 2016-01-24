<?php
/**
 * Created by PhpStorm.
 * User: j14003626
 * Date: 20/01/16
 * Time: 11:48
 */

$view->extend('layouts/layout');
$this->render('persists/header');

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

if (isset($users))
{
?>

    <div class="wrapper">
    <h1>Gestion des comptes</h1>
    <div class="Print">
    <h1>Les comptes disponibles</h1>

<?php


echo "<table border = 1> <tr bgcolor = #DDD>";
echo "<td>id</td><td>Pseudonyme</td><td>Action</td></tr>";

foreach ($users as $user)
{
    if (!($user instanceof UserEntity)) continue;
    echo '<tr bgcolor = #EEE>';
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

echo '</table></div></div>';
}



