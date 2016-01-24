<?php
/**
 * Created by PhpStorm.
 * User: maxbook
 * Date: 22/01/16
 * Time: 11:33
 */

?>

<div id="categories">
    <h2>Mes catégories</h2>
    <!-- <div class="item_cat"><img id="add_cat" src="<?php //View::getAsset('img/add_cat.png') ?>"></div>-->
    <a href="<?= View::getUrlFromRoute('favoris') ?>" class="item_cat"">Mes favoris</a>
    <a href="<?= View::getUrlFromRoute('blog') ?>" class="item_cat"">Mon blog</a>
    <?php
    /** @var CategoryEntity $category */
    if (!empty($categories))
    {
        foreach ($categories as $category)
        {
            ?>
            <a class="item_cat" href="<?= View::getUrlFromRoute('category/' . $category->getId()) ?>">
                <?= $category->getTitle() ?>
            </a>
            <?php
        }
    }
    ?>
</div>