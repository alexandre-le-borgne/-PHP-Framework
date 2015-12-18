<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/12/2015
 * Time: 09:17
 */
?>
<style>
    header {

    }
</style>
<header>
    <img src="https://raw.githubusercontent.com/poulfoged/WebApiExceptionPipeline/master/logo.png">
    <div>
        <h1>Une exception est survenue !</h1>
        <h2><?= $name ?></h2>
    </div>
</header>
<main>
    <h3>[<?= $code ?>] <?= $message ?></h3>
    <h4>In file : <?= $file ?></h4>
    <h5>Ligne : <?= $line ?></h5>
    <pre>
        <?php
        foreach($trace as $t) {
            echo $t."\n";
        };
        ?>
    </pre>
</main>