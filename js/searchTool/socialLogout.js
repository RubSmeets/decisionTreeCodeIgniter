/*
 * Javascript file that handles the social logout from google. (only loaded when user is logged in)
 * 
 * Function described here handle the interaction with the Google Client API and Code igniter backend for logging out a user. After successful logout
 * the user is redirect to the same page on the public section (logged out) 
 * 
 * resources:
 *      - https://developers.google.com/api-client-library/javascript/features/authentication
 *      - https://developers.google.com/api-client-library/javascript/reference/referencedocs#googleauthsignout
 */
(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0, // Success-code returned from code igniter server
        backEndPrivateURL: "http://localhost/crossmos_projects/decisionTree2/privateCon/"
    }
    /* Social logout functionality */
    var socialLogout = {
        /* Initialize variables */
        initVariables: function() {
            this.domCache = {};
            this.auth2 = null;
        },
        /* Cache frequently used DOM elements */
        cacheElements: function() {
            this.domCache.$socialSignOutBtn = $("#socialSignOut");
        },
        /* Initialize social login and logout functionality */
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
        /* bind DOM element events */
        bindEvents: function() {
            var that = this;
            // Handle signout button click
            this.domCache.$socialSignOutBtn.on('click', function() {
                that.socialSignOut();
                that.signOutGoogle();
            });
        },
        /* Sign out the user on the PHP (code igniter) backend */
        socialSignOut: function() {
            // Send the code to the server
            $.ajax({
                method: 'GET',
                url: CONST.backEndPrivateURL + 'AJ_logout',
                dataType: "json",
                success: socialLogin.serverSignoutCallback
            });
        },
        /* Sign out the user on google for this application */
        //NOTE that sign out will not work if you are running from localhost.
        signOutGoogle: function() {
            var that = this;
            if (that.auth2.isSignedIn.get()) {
                this.auth2.signOut().then(function() {
                    that.resetLoginState();
                });
            }
        },
        /* After successful sign out redirect user to public page */
        serverSignoutCallback: function(response) {
            if(response.srvResponseCode === CONST.successCode) {
                // redirect to public part
                window.location = response.srvMessage;
            }
        }
    }
    /* Wait for document ready before Initializing log out functionality */
    $( document ).ready(function() {
        socialLogout.init();
    });

})(jQuery, window, document);


