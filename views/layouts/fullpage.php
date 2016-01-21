<?php
$view->extend('layouts/layout');
?>
<div id="fullpage">

    <?php
    echo $_content;
    $this->render('layouts/section_news');
    $this->render('layouts/section_content');
    $this->render('layouts/section_social');
    $this->render('layouts/section_contact');
    ?>
</div>
<script>
    $(document).ready(function() {
        $('#fullpage').fullpage();
    });
</script>