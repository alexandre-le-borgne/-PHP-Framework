<!DOCTYPE html>
<!--
variables disponibles en revenant de UserController.preRegisterFormAction() :  $errors, $username
-->
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
            <?php View::getView("forms/preRegisterForm", isset($errors) ? array('errors' => $errors) : null); ?><!--pour les warnings-->
        </div>

        <div class="section section_news">
            <div id="title_section_news">
                <h1>Profitez de<br>vos journaux preferés.</h1>
                <h4>Restez toujours informés et<br>profitez de point de vue <br>ifférent.</h4>
                <div id="button_action">DECOUVREZ AARON</div>
            </div>
            <div class="newspaper">
                <div id="time_magazine">          <img src="img/newspapers/time.png">         </div>
                <div id="usatoday_magazine">      <img src="img/newspapers/usatoday.png">     </div>
                <div id="lemonde_magazine">       <img src="img/newspapers/lemonde.png">      </div>
                <div id="courrierinter_magazine"> <img src="img/newspapers/courrierinter.png"></div>
                <div id="liberation_magazine">    <img src="img/newspapers/liberation.png">   </div>
                <div id="figaro_magazine">        <img src="img/newspapers/figaro.jpg">       </div>
            </div>
        </div>

        <div class="section section_content">
            <div id="title_section_content">
                <h1>Suivez les<br>contenus<br>que vous<br>aimez.</h1>
                <h4>Suivez vos artistes, journaux,<br> blogs ou personnalités<br> préférées.</h4>
                <div id="button_action">INSCRIVEZ VOUS !</div>
            </div>
            <div class="content">
                <div id="barrack_content">          <img src="img/content/barrack.jpg">      </div>
                <div id="charlie_content">          <img src="img/content/charlie.jpg">      </div>
                <div id="robin_content">            <img src="img/content/robin.jpg">        </div>
                <div id="gims_content">             <img src="img/content/gims.png">         </div>
                <div id="david_guetta_content">     <img src="img/content/david.png">        </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#fullpage').fullpage();
        });
    </script>
</body>
</html>