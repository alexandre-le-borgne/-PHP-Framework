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
                <script type="text/javascript">
                    function fblogout() {
                        FB.logout(function () {
                            window.location.reload(); });
                    }
                    window.fbAsyncInit = function() {
                        FB.init({
                            appId   : '<?php echo $facebook->getAppId(); ?>',
                            session : <?php echo json_encode($session); ?>,
                            status  : true,
                            cookie  : true,
                            xfbml   : true
                        });

                        FB.Event.subscribe('auth.login', function() {
                            window.location.reload();
                        });
                    };

                    (function() {
                        var e = document.createElement('script');
                        e.src = document.location.protocol + '//connect.facebook.net/fr_FR/all.js';
                        e.async = true;
                        document.getElementById('fb-root').appendChild(e);
                    }());
                    //your fb login function
                    function fblogin() {
                        FB.login(function(response) {
                            //...
                        }, {perms:'read_stream,publish_stream,offline_access'});
                        redir();
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