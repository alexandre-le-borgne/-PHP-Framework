<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 24/01/2016
 * Time: 18:12
 */
?>
<div id="aside">
        <?php
        if (isset($categories))
            $this->render('persists/categories', array('categories' => $categories));
        var_dump($streams);
        if (isset($streams))
            $this->render('persists/streams', $streams);
        ?>
</div>
<script>
    (function ($) {
        $(window).load(function () {
            $("#aside").mCustomScrollbar();
        });
    })(jQuery);
</script>