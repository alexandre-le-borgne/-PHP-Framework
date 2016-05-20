<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Une exception est survenue !</title>
    <style>
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #607D8B;
        }

        #content {
            width: 70%;
            margin: auto;
            background-color: snow;
            padding: 20px;
            margin-top: 20px;
        }

        .table {
            display: table;
            table-layout: fixed;
            width: 100%;
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
            border-bottom: 1px solid #CA412F;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        main {
            margin-top: 30px;
        }

        p {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        h1 {
            color: #CA412F;
        }

        h2 {
            color: #F44336;
        }

        h3 {
            color: #495E68;
        }

        h4, h5 {
            color: #607D8B;
        }
    </style>
</head>

<body>
<div id="content">
    <header class="table">
        <div class="table_col table_col_left">
            <img src="https://raw.githubusercontent.com/poulfoged/WebApiExceptionPipeline/master/logo.png">
        </div>
        <div class="table_col table_col_right">
            <h1>Une exception est survenue !</h1>
            <h2><?= $name ?></h2>
        </div>
    </header>
    <main>
        <h3>[<?= $code ?>] <?= $message ?></h3>
        <h4>Fichier : <?= $file ?></h4>
        <h5>Ligne : <?= $line ?></h5>
        <p>
            <?php
            for ($i = count($trace) - 1; $i >= 0; --$i)
            {
                echo ($i + 1) . ') ' . $trace[$i] . "\n<br>";
            };
            ?>
        </p>
    </main>
</div>
</body>
</html>