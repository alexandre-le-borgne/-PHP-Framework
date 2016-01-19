<?php
$view->extend('layouts/layout');
?>
<div id="fullpage">
    <div class="section">

        <?= $_content ?>

        <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
        <script>
            function onSignIn(googleUser) {
                // Useful data for your client-side scripts:
                var profile = googleUser.getBasicProfile();
                console.log("ID: " + profile.getId()); // Don't send this directly to your server!
                console.log("Name: " + profile.getName());
                console.log("Image URL: " + profile.getImageUrl());
                console.log("Email: " + profile.getEmail());

                // The ID token you need to pass to your backend:
                var id_token = googleUser.getAuthResponse().id_token;
                console.log("ID Token: " + id_token);
            };
        </script>

        <div id="my-signin2"></div>
        <script>
            function onSuccess(googleUser) {
                console.log('Logged in as: ' + googleUser.getBasicProfile().getName());
            }
            function onFailure(error) {
                console.log(error);
            }
            function renderButton() {
                gapi.signin2.render('my-signin2', {
                    'scope': 'https://www.googleapis.com/auth/plus.login',
                    'width': 200,
                    'height': 50,
                    'longtitle': true,
                    'theme': 'dark',
                    'onsuccess': onSuccess,
                    'onfailure': onFailure
                });
            }
        </script>

        <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>


        <div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '1695359537375763',
                    xfbml      : true,
                    version    : 'v2.5'
                });
            };

            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.5";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
        <div class="fb-login-button" data-max-rows="1" data-size="medium" data-show-faces="true" data-auto-logout-link="true"></div>
        <div class="fb-like" data-share="true" data-width="450" data-show-faces="true"></div>
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