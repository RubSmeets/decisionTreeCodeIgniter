/*
 * Javascript file that handles the social login from google. (only loaded when user is logged out)
 * 
 * Google APIs client library for Javascript provides a Simple authentication mechanism that does not require OAuth 2.0 credetial setup.
 * The simple authentication only allows the application to authenticate a user through the google API and gain access to profile data (email, username).
 * For more information on how the API works refer to the first link in resources below.
 * 
 * Function described here handle the interaction with the Google Client API and Code igniter backend for logging in a user. After successful login
 * the user is redirect to the same page on the private section (logged in) 
 * 
 * resources:
 *      - https://developers.google.com/api-client-library/javascript/features/authentication
 */
(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0, // Success-code returned from code igniter server
        backEndBaseURL: "http://localhost/crossmos_projects/decisionTree2/publicCon/"
    }
    /* Google login functionality provided by google basic API */
    var socialLogin = {
        /* Initialize variables */
        initVariables: function() {
            this.auth2 = null;
            this.domCache = {};
        },
        /* Cache frequently used DOM elements */
        cacheElements: function() {
            this.domCache.$googleLoginBtn = $("#googleLoginBtn");
        },
        /* Initialize socialLogin functionality */
        init: function() {
            var that = this;
            
            this.initVariables();
            this.cacheElements();
            //Init the google authentication api
            gapi.load('auth2', function(){
                // Retrieve the singleton for the GoogleAuth library and set up the client.
                that.auth2 = gapi.auth2.init({
                    client_id: '814864177982-qhde0ik7hkaandtoaaa0515niinslg94.apps.googleusercontent.com',
                    cookie_policy: 'single_host_origin'
                });
                that.bindEvents();
            });
        },
        /* Bind DOM element events */
        bindEvents: function() {
            var that = this;
            // Login button click event
            this.domCache.$googleLoginBtn.on('click', function() {
                //Sign in the user
                that.auth2.signIn().then(function(googleUser) {
                    socialLogin.googleSigninCallback(googleUser);
                });
            });

        },
        /* Callback from Google SignIn */
        googleSigninCallback: function(googleUser) {
            var userProfile = googleUser.getBasicProfile();
            var id_token = googleUser.getAuthResponse().id_token;
            // Send the id_token to server for furhter authentication
            $.ajax({
                method: 'POST',
                url: CONST.backEndBaseURL + 'AJ_login',
                dataType: "json",
                success: socialLogin.serverSigninCallback,
                data: {idtoken: id_token}
            });
        },
        /* Handle server Callback and redirect page to private if login was successful */
        serverSigninCallback: function(response) {
            // Handle or verify the server response.
            if(response.srvResponseCode === CONST.successCode) {
                // redirect to private part
                window.location = response.srvMessage;
            }
        }
    }
    /* Initialize the login functionality when document is ready */
    $( document ).ready(function() {
        socialLogin.init();
    });

})(jQuery, window, document);