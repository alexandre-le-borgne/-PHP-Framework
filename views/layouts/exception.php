<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/12/2015
 * Time: 09:17
 */
?>
<style>
    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    .table {
        display: table;
        table-layout: fixed;
        padding: 0;
    }

    .table_col {
        list-style: none;
        display: table-cell;
        vertical-align: top;
    }

    .table_col_left {
        width: 128px;
    }

    .table_col_right {
        padding-left: 10px;
        vertical-align: middle;
    }

    img {
        border: none;
        max-width: 100%;
    }

    header {

        border-bottom: 1px solid grey;
        padding-bottom: 10px;
        min-width: 70%;
        margin: auto;
    }
</style>
<header class="table">
    <div class="table_col table_col_left">
        <img src="https://raw.githubusercontent.com/poulfoged/WebApiExceptionPipeline/master/logo.png">
    </div>
    <div class="table_col table_col_right">
        <h1>Une exception est survenue !</h1>
        <h2><?= $name ?></h2>
    </div>
</header>
<content>
    <h3>[<?= $code ?>] <?= $message ?></h3>
    <h4>In file : <?= $file ?></h4>
    <h5>Ligne : <?= $line ?></h5>
    <pre><?php
        foreach(array_reverse($trace) as $t) {
            echo $t."\n";
        };
    ?></pre>
</content>