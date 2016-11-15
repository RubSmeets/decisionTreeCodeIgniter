(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0,
        backEndBaseURL: "http://localhost/crossmos_projects/decisionTree2/publicCon/"
    }

    var socialLogin = {
        initVariables: function() {
            this.auth2 = null;
            this.domCache = {};
        },

        cacheElements: function() {
            this.domCache.$googleLoginBtn = $("#googleLoginBtn");
        },

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

        bindEvents: function() {
            var that = this;

            this.domCache.$googleLoginBtn.on('click', function() {
                //Sign in the user
                that.auth2.signIn().then(function(googleUser) {
                    socialLogin.googleSigninCallback(googleUser);
                });
            });

        },

        googleSigninCallback: function(googleUser) {
            var userProfile = googleUser.getBasicProfile();
            var id_token = googleUser.getAuthResponse().id_token;
            // Send the code to the server
            $.ajax({
                method: 'POST',
                url: CONST.backEndBaseURL + 'AJ_login',
                dataType: "json",
                success: socialLogin.serverSigninCallback,
                data: {idtoken: id_token}
            });
        },

        serverSigninCallback: function(response) {
            // Handle or verify the server response.
            if(response.srvResponseCode === CONST.successCode) {
                // redirect to private part
                window.location = response.srvMessage;
            }
        }
    }

    $( document ).ready(function() {
        socialLogin.init();
    });

})(jQuery, window, document);