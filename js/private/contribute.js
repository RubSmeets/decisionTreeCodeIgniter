
(function($, window, document, undefined) {
    'use strict';

    var CONST = {
        successCode: 0,
        validatedFields: {
            step1: 5,
            step2: 5,
            step3: 0,
            step4: 0,
            step5: 5
        },
        formSteps: 5,
        buttonNavOption: 0,
        progressNavOption: 1,
        moveForward: 1,
        moveBack: -1,
        backEndBaseURL: "http://localhost/crossmos_projects/decisionTree2/publicCon/",
        backEndPrivateURL: "http://localhost/crossmos_projects/decisionTree2/privateCon/"
    }

    /* Main contribute functionality */
    var main = {
        initVariables: function() {
            this.addState = 1;
            this.validFieldCount = {
                step1: 0,
                step2: 0,
                step3: 0,
                step4: 0,
                step5: 0
            }
            this.filterTerms = [];
            this.comparedItems = [];
            this.domCache = {};
        },

        cacheElements: function() {
            this.domCache.$contributeOptions = $('[name="options"]');     
            this.domCache.$addForm = $('#addForm');
            this.domCache.$editForm = $('#editForm');
            this.domCache.$goNextAddBtn = $('#goNextStepAdd');
            this.domCache.$goBackAddBtn = $('#goBackStepAdd').hide();
            this.domCache.$validatedFormFieldsStep1 = $('.container-step1 .has-feedback');
            this.domCache.$progressSegments = $('.progress-bar');    
        },

        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();

            this.initTooltip();
        },
        // Mandatory Javascript init of bootstrap tooltip component
        initTooltip: function() {
            $('[data-toggle="tooltip"]').tooltip();
        },

        bindEvents: function() {
            var that = this;
            
            this.domCache.$contributeOptions.on('change', function() {
                if($(this).val() === "add") {
                    that.showAddForm();
                } else {
                    that.showEditForm();
                }
            });
            // button form nav
            this.domCache.$goNextAddBtn.on('click', function() {
                that.goNavStep(CONST.moveForward, CONST.buttonNavOption);
            });
            this.domCache.$goBackAddBtn.on('click', function() {
                that.goNavStep(CONST.moveBack, CONST.buttonNavOption);
            });
            //form field validation events
            this.domCache.$validatedFormFieldsStep1.validator().on('invalid.bs.validator', function() {
                if(that.validFieldCount['step1'] > 0) that.validFieldCount['step1'] -= 1;
            });
            this.domCache.$validatedFormFieldsStep1.validator().on('valid.bs.validator', function() {
                that.validFieldCount['step1'] += 1;
            });
            //progress bar nav
            this.domCache.$progressSegments.on('click', function() {
                var step = $(this).data('myValue');
                that.goNavStep(step, CONST.progressNavOption);
            });
        },

        goNavStep: function(step, navOption) {
            var validCount = 0;

            //hide current container
            $('.container-step' + this.addState).hide();
            //remove validation classes current
            $('.progress-step' + this.addState).removeClass('active-step valid-step faulty-step');
            //get valid count of current
            validCount = this.validFieldCount['step' + this.addState] + this.getCountEmptyValidationFields(this.addState);
            if(validCount !== CONST.validatedFields['step' + this.addState]) { // depending on validation result
                $('.progress-step' + this.addState).addClass('faulty-step'); 
            } else {
                $('.progress-step' + this.addState).addClass('valid-step');
            }

            // go to next
            if(navOption === CONST.buttonNavOption) {
                this.addState += step;
            } else {
                this.addState = step;
            }
            // Show next container
            $('.container-step' + this.addState).show();
            $('.progress-step' + this.addState).removeClass('active-step valid-step faulty-step');
            $('.progress-step' + this.addState).addClass('active-step');
            
            // Figure-out previous/next button visibility
            this.figureOutNavBtn();
        },

        getCountEmptyValidationFields: function(step) {
            return $('.container-step' + step + ' .has-feedback.not-required').filter(function() { return $(this).val() == ""; }).length;
        },

        figureOutNavBtn: function() {
            if(this.addState > 1) {
                this.domCache.$goBackAddBtn.show();
            } else {
                this.domCache.$goBackAddBtn.hide();
            }
            if(this.addState >= CONST.formSteps) {
                this.domCache.$goNextAddBtn.hide();
            } else {
                this.domCache.$goNextAddBtn.show();
            } 
        },

        showAddForm: function() {
            if(!this.domCache.$addForm.is(":visible")) {
                this.domCache.$addForm.show();
                this.domCache.$editForm.hide();
            }
        },

        showEditForm: function() {
            if(!this.domCache.$editForm.is(":visible")) {
                this.domCache.$editForm.show();
                this.domCache.$addForm.hide();
            }
        },
    }

    var socialLogin = {
        initVariables: function() {
            this.domCache = {};
        },

        cacheElements: function() {
            this.domCache.$socialSignOutBtn = $("#socialSignOut");
        },

        init: function() {
            this.initVariables();
            this.cacheElements();
            this.bindEvents();
        },

        bindEvents: function() {
            var that = this;

            this.domCache.$socialSignOutBtn.on('click', function() {
                that.socialSignOut();
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

        serverSignoutCallback: function(response) {
            if(response.srvResponseCode === CONST.successCode) {
                // redirect to public part
                window.location = response.srvMessage;
            }
        }
    }

    $( document ).ready(function() {
        main.init();
        socialLogin.init();
    });

})(jQuery, window, document);