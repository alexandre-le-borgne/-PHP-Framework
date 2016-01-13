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
    <script>
        // This is called with the results from from FB.getLoginStatus().
        function statusChangeCallback(response) {
            console.log('statusChangeCallback');
            console.log(response);
            // The response object is returned with a status field that lets the
            // app know the current login status of the person.
            // Full docs on the response object can be found in the documentation
            // for FB.getLoginStatus().
            if (response.status === 'connected') {
                // Logged into your app and Facebook.
                testAPI();
            } else if (response.status === 'not_authorized') {
                // The person is logged into Facebook, but not your app.
                document.getElementById('status').innerHTML = 'Please log ' +
                    'into this app.';
            } else {
                // The person is not logged into Facebook, so we're not sure if
                // they are logged into this app or not.
                document.getElementById('status').innerHTML = 'Please log ' +
                    'into Facebook.';
            }
        }

        // This function is called when someone finishes with the Login
        // Button.  See the onlogin handler attached to it in the sample
        // code below.
        function checkLoginState() {
            FB.getLoginStatus(function(response) {
                statusChangeCallback(response);
            });
        }

        window.fbAsyncInit = function() {
            FB.init({
                appId      : '1148334785195709',
                xfbml      : true,
                version    : 'v2.5'
            });
        };

            // Now that we've initialized the JavaScript SDK, we call
            // FB.getLoginStatus().  This function gets the state of the
            // person visiting this page and can return one of three states to
            // the callback you provide.  They can be:
            //
            // 1. Logged into your app ('connected')
            // 2. Logged into Facebook, but not your app ('not_authorized')
            // 3. Not logged into Facebook and can't tell if they are logged into
            //    your app or not.
            //
            // These three cases are handled in the callback function.

            FB.getLoginStatus(function(response) {
                statusChangeCallback(response);
            });

        };

        // Load the SDK asynchronously
        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        // Here we run a very simple test of the Graph API after login is
        // successful.  See statusChangeCallback() for when this call is made.
        function testAPI() {
            console.log('Welcome!  Fetching your information.... ');
            FB.api('/me', function(response) {
                console.log('Successful login for: ' + response.name);
                document.getElementById('status').innerHTML =
                    'Thanks for logging in, ' + response.name + '!';
            });
        }
    </script>

    <div id="fullpage">
        <div
            class="fb-like"
            data-share="true"
            data-width="450"
            data-show-faces="true">
        </div>

        <login-button scope="public_profile,email" onlogin="checkLoginState();">
        </login-button>

        <div id="status">
        </div>

        <?php View::getView("persists/header"); ?>

        <?php
        if(Request::getInstance()->getSession()->isConnected()) {
            echo "CONNECTER!";
        }
        else {
            ?>
            <div class="section">
                <?php View::getView("forms/loginForm"); ?>
                <?php View::getView("forms/preRegisterForm", isset($errors) ? array('errors' => $errors) : null); ?><!--pour les warnings-->
            </div>
            <?php
        }
        ?>

        <div class="section section_news">
            <div id="title_section_news">
                <h1>Profitez de<br>vos journaux preferés.</h1>
                <h4>Restez toujours informés et<br>profitez de point de vue <br>ifférent.</h4>
                <div id="button_action">DECOUVREZ AARON</div>
            </div>
            <div class="newspaper">
                <table>
                    <tr>
                        <div id="time_magazine">          <img src="web/img/newspapers/time.png">         </div>
                        <div id="usatoday_magazine">      <img src="web/img/newspapers/usatoday.png">     </div>
                    </tr>
                    <tr>
                        <div id="lemonde_magazine">       <img src="web/img/newspapers/lemonde.png">      </div>
                        <div id="courrierinter_magazine"> <img src="web/img/newspapers/courrierinter.png"></div>
                    </tr>
                    <tr>
                        <div id="liberation_magazine">    <img src="web/img/newspapers/liberation.png">   </div>
                        <div id="figaro_magazine">        <img src="web/img/newspapers/figaro.jpg">       </div>
                    </tr>
                </table>
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