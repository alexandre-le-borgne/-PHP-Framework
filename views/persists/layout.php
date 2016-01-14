<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 13/01/2016
 * Time: 11:29
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <?php $this->render('persists/head'); ?>
    </head>
    <body>

        <div id="fullpage">
            <div class="section">
                <?php

                /** En attendant que ça remarche*/
                View::getView('forms/loginForm');
                View::getView('forms/preRegisterForm', $this->output('errors'));
                /** Fin */
                //                echo $this->output('_content');
                ?>
                <div id="fb-root"></div>
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
                            appId      : '{your-app-id}',
                            cookie     : true,  // enable cookies to allow the server to access
                                                // the session
                            xfbml      : true,  // parse social plugins on this page
                            version    : 'v2.2' // use version 2.2
                        });

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
                    (function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
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

                <!--
                  Below we include the Login Button social plugin. This button uses
                  the JavaScript SDK to present a graphical Login button that triggers
                  the FB.login() function when clicked.
                -->

                <vendor/facebook:login-button scope="public_profile,email" onlogin="checkLoginState();">
                </vendor/facebook:login-button>

            </div>

            <div class="section section_news">
                <div id="title_section_news">
                    <div id="text_content">
                        <h1>Profitez de vos journaux preferés.</h1><br>
                        <h3>Restez toujours informés et profitez de point de vue différent.</h3>
                        <br><div id="button_action">Découvrez Aaron !</div>
                    </div>
                </div>

                <div id="table_section_news">
                    <table width=345px height=100%>
                        <tr>
                            <td align="center" id="time_magazine"><img src="web/img/newspapers/time.png"></td>
                            <td align="center" id="usatoday_magazine"><img src="web/img/newspapers/usatoday.png"></td>
                        </tr>
                        <tr>
                            <td align="center" id="lemonde_magazine"><img src="web/img/newspapers/lemonde.png"></td>
                            <td align="center" id="courrierinter_magazine"> <img src="web/img/newspapers/courrierinter.png"></td>
                        </tr>
                        <tr>
                            <td align="center" id="liberation_magazine"><img src="web/img/newspapers/liberation.png"></td>
                            <td align="center" id="figaro_magazine"><img src="web/img/newspapers/figaro.jpg"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="section section_content">
                <div id="title_section_content">
                    <div id="text_content">
                        <h1>Suivez le contenu que vous aimez.</h1><br>
                        <h3>Suivez vos artistes, journaux,<br> blogs ou personnalités<br> préférées.</h3>
                        <br><div id="button_action">Inscrivez-vous !</div>
                    </div>
                </div>

                <div id="table_section_content">
                    <table width=345px height=66%>
                        <tr>
                            <td width=50% align="center" id="barrack_content"><img src="web/img/content/time.png"></td>
                            <td width=50% align="center" id="charlie_content"><img src="web/img/content/charlie.png"></td>
                        </tr>
                    </table>
                    <table width=345px height=34% >
                        <tr>
                            <td width="33%" align="center" id="robin_content"><img src="web/img/content/robin.png"></td>
                            <td width="33%" align="center" id="gims_content"><img src="web/img/content/acdc.png"></td>
                            <td width="33%" align="center" id="david_guetta_content"><img src="web/img/content/david.png"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="section section_social">
                <div id="title_section_social">
                    <div id="text_content">
                        <h1>Vos réseaux sociaux enfin réunis.</h1><br>
                        <h3>Retrouvez tout vos réseaux sociaux préférés comme Facebook, Twitter ou Pinterest</h3>
                    </div>
                </div>

                <div id="table_section_news">
                    <table width=345px height=100%>
                        <tr>
                            <td align="center" id="time_magazine"><img src="web/img/newspapers/time.png"></td>
                            <td align="center" id="usatoday_magazine"><img src="web/img/newspapers/usatoday.png"></td>
                        </tr>
                        <tr>
                            <td align="center" id="lemonde_magazine"><img src="web/img/newspapers/lemonde.png"></td>
                            <td align="center" id="courrierinter_magazine"> <img src="web/img/newspapers/courrierinter.png"></td>
                        </tr>
                        <tr>
                            <td align="center" id="liberation_magazine"><img src="web/img/newspapers/liberation.png"></td>
                            <td align="center" id="figaro_magazine"><img src="web/img/newspapers/figaro.jpg"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="section contact">
                <div id="contact">

                </div>

                <div id="footer">

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