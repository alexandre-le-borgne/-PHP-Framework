<?php
/**
 * un formulaire d’authentification par pseudonyme et mot de passe
 * et avec compte Google, Facebook ou Twitter
 */
?>
<div class="loginDiv">

    <h4>Connectez-vous ?</h4>
    <!--SIGN IN FORM-->

    <form class="form-horizontal" method="post" action="login">
        <!--USERNAME-->
        <input type="text" name="login" placeholder="Identifiant ou Email"><br><br>
        <!--PASSWORD-->
        <input type="password"  name="password" placeholder="Password"><br><br>
        <!--SUBMIT-->
        <input class="btn" type="submit" value="Submit">
        <!--SUBMIT-->
        <label class="checkbox">
            <input type="checkbox"> Remember me
        </label>
        <a class="forgot" href="./forgotForm.php">Mot de passe oublié ?</a>
        <hr>
        <!--SOCIAL CONNECT-->
        <div class="socialConnect">
<!--            <button type="submit"><img src="web/img/fb_icon_325x325.png" class="img-rounded"></button>-->
<!--            <button type="submit"><img src="web/img/share-googleplus.png" class="img-rounded"></button>-->
            <div class="fb-login-button" data-max-rows="1" data-size="medium" data-show-faces="true" data-auto-logout-link="true"></div>
            <!-- Add where you want your sign-in button to render -->
            <!-- Use an image that follows the branding guidelines in a real app -->
            <button id="signinButton">Sign in with Google</button>
            <script>
                $('#signinButton').click(function() {
                    // signInCallback defined in step 6.
                    auth2.grantOfflineAccess({'redirect_uri': 'postmessage'}).then(signInCallback);
                });
            </script>
            <div id="result"></div>
            <script>
                /**
                 * The Sign-In client object.
                 */

                function start() {
                    gapi.load('auth2', function() {
                        auth2 = gapi.auth2.init({
                            client_id: '150676207911-artsrukbljruts6t2t0675q8c1l4o8av.apps.googleusercontent.com',
                            cookiepolicy: 'single_host_origin', /** Default value **/
                            scope: 'profile'
                            // Scopes to request in addition to 'profile' and 'email'
                            //scope: 'additional_scope'
                        });
                    });
                }

                var auth2;

                /**
                 * Initializes the Sign-In client.
                 */
                var initClient = function() {
                    gapi.load('auth2', function(){
                        /**
                         * Retrieve the singleton for the GoogleAuth library and set up the
                         * client.
                         */
                        auth2 = gapi.auth2.init({
                            client_id: '150676207911-artsrukbljruts6t2t0675q8c1l4o8av.apps.googleusercontent.com',
                            cookiepolicy: 'single_host_origin', /** Default value **/
                            scope: 'profile'
                        });

                        // Attach the click handler to the sign-in button
                        auth2.attachClickHandler('signin-button', {}, onSuccess, onFailure);
                    });
                };

                /**
                 * Handle successful sign-ins.
                 */
                var onSuccess = function(user) {
                    console.log('Signed in as ' + user.getBasicProfile().getName());
                };

                /**
                 * Handle sign-in failures.
                 */
                var onFailure = function(error) {
                    console.log(error);
                };

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
        </div>
    </form>
</div>