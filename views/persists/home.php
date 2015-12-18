<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Mon blog</title>
    <?php View::getView("persists/head"); ?>
</head>

<body>
    <div id="fullpage">
        <?php View::getView("persists/header"); ?>
        <div class="section">
            <?php View::getView("forms/loginForm"); ?>
            <?php View::getView("forms/preRegisterForm"); ?>
        </div>
        <div class="section">
            <h1>HOME</h1>
            <div class="slide"> Slide 1 </div>
            <div class="slide"> Slide 2 </div>
            <div class="slide"> Slide 3 </div>
            <div class="slide"> Slide 4 </div>
        </div>
        <div class="section">
            <h1>RECUPERER VOS FLUX DE DONNE</h1>
        </div>
        <div class="section">
            <h1>RECUPERER VOS FLUX DE DONNE</h1>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#fullpage').fullpage();
        });
    </script>
</body>
</html>