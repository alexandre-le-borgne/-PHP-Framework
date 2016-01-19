<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 19/01/2016
 * Time: 23:29
 */
?>
<button id="signinButton">Sign in with Google</button>
<script>
    $('#signinButton').click(function () {
        // signInCallback defined in step 6.
        auth2.grantOfflineAccess({'redirect_uri': '/aaron/google'}).then(signInCallback);
    });
</script>
<div id="result"></div>
<script>
    /**
     * The Sign-In client object.
     */

    function start() {
        gapi.load('auth2', function () {
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
    var initClient = function () {
        gapi.load('auth2', function () {
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
    var onSuccess = function (user) {
        console.log('Signed in as ' + user.getBasicProfile().getName());
    };

    /**
     * Handle sign-in failures.
     */
    var onFailure = function (error) {
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