<?php
/**
 * Created by PhpStorm.
 * User: j14003626
 * Date: 20/01/16
 * Time: 11:48
 */

if (!isset($users))
    throw new Exception('Erreur manageUsers.php : $users n\'estpas set');

?>

    <div class="wrapper">
    <h1>Gestion des comptes</h1>
    <div class="Print">
    <h1>Les comptes disponibles</h1>

<?php


echo "<table border = 1> <tr bgcolor = #118218>";
echo "<td>id</td><td>Pseudonyme</td><td>Action</td></tr>";

foreach ($users as $user):
{
    if (!($user instanceof UserEntity)) continue;
    echo '<tr bgcolor=' . use_color() . '>';
    echo "<td>" . $user->getId() . "</td>";
    echo "<td>" . $user->getUsername() . "</td>";
    ?>
    <td>
        <form action="yapadactiontrololol" method="post">
            <input type="hidden" name="id" value="<?php echo $user->getId(); ?>">
            <input type="submit" value="Supprimer" name="delUser">
            <input type="submit" value="faire autre chose" name="other">
        </form>
    </td>

    <?php
    echo '</tr>';
}
endforeach;

echo '</table></div></div>';


