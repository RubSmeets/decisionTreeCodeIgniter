(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0,
        backEndPrivateURL: "http://localhost/crossmos_projects/decisionTree2/privateCon/"
    }

    var socialLogin = {
        initVariables: function() {
            this.domCache = {};
            this.auth2 = null;
        },

        cacheElements: function() {
            this.domCache.$socialSignOutBtn = $("#socialSignOut");
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

            this.domCache.$socialSignOutBtn.on('click', function() {
                that.socialSignOut();
                that.signOutGoogle();
            });
        },

        socialSignOut: function() {
            // Send the code to the server
            $.ajax({
                method: 'GET',
                url: CONST.backEndPrivateURL + 'AJ_logout',
                dataType: "json",
                success: socialLogin.serverSignoutCallback
            });
        },
        //NOTE that sign out will not work if you are running from localhost.
        signOutGoogle: function() {
            var that = this;
            if (that.auth2.isSignedIn.get()) {
                this.auth2.signOut().then(function() {
                    that.resetLoginState();
                });
            }
        },

        serverSignoutCallback: function(response) {
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


