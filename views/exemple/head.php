<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 12/01/2016
 * Time: 20:26
 */
?>
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>

    <link rel="stylesheet" href="<?= View::getAsset('inc/fullPage/jquery.fullPage.css') ?>" />
    <link rel="stylesheet" href="<?= View::getAsset('css/bootstrap.css') ?>">
    <link rel="stylesheet" href="<?= View::getAsset('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= View::getAsset('css/bootstrap-responsive.css') ?>">
    <link rel="stylesheet" href="<?= View::getAsset('css/bootstrap-responsive.min.css') ?>">
    <link rel="stylesheet" href="<?= View::getAsset('css/body.css') ?>">
    <link rel="stylesheet" href="<?= View::getAsset('css/footer.css') ?>">
    <link rel="stylesheet" href="<?= View::getAsset('css/form.css') ?>">
    <link rel="stylesheet" href="<?= View::getAsset('css/nav.css') ?>">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?= View::getAsset('inc/fullPage/vendors/jquery.easings.min.js') ?>"></script>
    <script type="text/javascript" src="<?= View::getAsset('inc/fullPage/vendors/jquery.slimscroll.min.js') ?>"></script>
    <script type="text/javascript" src="<?= View::getAsset('inc/fullPage/jquery.fullPage.js') ?>"></script>
    <script type="text/javascript" src="<?= View::getAsset('js/slide.js') ?>"></script>
    <script type="text/javascript" src="<?= View::getAsset('js/script.js') ?>"></script>
</head>
<?php
print_r(get_defined_vars());