<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 22/01/16
 * Time: 11:33
 */

?>

<div id="categories">
    <a class="item_cat" href="<?= View::getUrlFromRoute('categoryadd') ?>"><img id="add_cat" src="web/img/add_cat.png"></a>
    <?php
    /** @var CategoryEntity $category */
    if(!empty($categories)) {
        foreach($categories as $category)
        {
            ?>
            <a class="item_cat" href="<?= View::getUrlFromRoute('streamadd') ?>">Ajouter un flux</a>
            <?php
        }
    }
    ?>
</div>
