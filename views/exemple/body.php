<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 13/01/2016
 * Time: 14:47
 */
/**
 * @var $this ViewPart
 */
View::extend('exemple/layout');
?>
<body>
    <h1><?= View::output('title', 'Titre par défaut') ?></h1>
    <h2><?= View::output('title2', 'Sous-Titre par défaut') ?></h2>
    <?php if(View::isGranted(Session::USER_IS_CONNECTED)): ?>
        L'utilisateur est connecté !
    <?php endif; ?>
    <?= View::getChildContent() ?>
    <hr>
    <?= View::render('exemple/content'); ?>
    <hr>
</body>
